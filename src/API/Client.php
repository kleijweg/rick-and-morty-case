<?php


namespace App\API;

use App\Model\ModelInterface;
use Generator;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Uri;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 *
 * @package App\API
 */
class Client
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var array|string[]
     */
    protected $allowedFilters = [];

    /**
     * Currently applied filters
     *
     * @var array|string[]
     */
    protected $filters = [];

    /**
     * @var ResultInterface|null
     */
    protected $lastResult;

    /**
     * @var callable|null
     */
    protected $callback;

    /**
     * @var bool
     */
    private $caching = true;

    /**
     * Client constructor.
     *
     * @param HttpClient        $httpClient
     * @param string            $endpoint
     * @param ResultFactory     $resultFactory
     * @param array|string[]    $allowedFilters
     */
    public function __construct(
        HttpClient $httpClient,
        string $endpoint,
        ResultFactory $resultFactory,
        array $allowedFilters
    ) {
        $this->httpClient = $httpClient;
        $this->endpoint = $endpoint;
        $this->resultFactory = $resultFactory;
        $this->allowedFilters = $allowedFilters;
    }

    /**
     * Get the configured endpoint for this client.
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Get the filters that are allowed for this client.
     *
     * @return array|string[]
     */
    public function getAllowedFilters(): array
    {
        return $this->allowedFilters;
    }

    /**
     * Set the callback function for displaying progress.
     *
     * @param  callable $callback
     * @return $this
     */
    public function setCallBack(callable $callback): self
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * Enable/disable caching
     *
     * @param bool $caching
     * @return $this
     */
    public function setCaching(bool $caching = true): self
    {
        $this->caching = $caching;
        return $this;
    }

    /**
     * Let a caller know of the progress we're making.
     *
     * @param mixed ...$params
     */
    private function doCallback(...$params): void
    {
        if (is_callable($this->callback)) {
            call_user_func($this->callback, ...$params);
        }
    }

    /**
     * Get the results.
     *
     * @return ResultInterface
     */
    public function list(): ResultInterface
    {
        // seems to be working
        $options = array_merge(
            ['query' => $this->filters],
            $this->caching ? [] : ['headers' => [CacheMiddleware::HEADER_RE_VALIDATION => 'yes']]
        );
        $response = $this->httpClient->request('GET', $this->endpoint, $options);
        return $this->createResult($response);
    }

    /**
     * Get all items
     *
     * @return Generator<ModelInterface>
     */
    public function getAll(): Generator
    {
        $list = $this->list();
        $counter = 0;
        while ($list instanceof PagedResultInterface) {
            foreach ($list->getResults() as $result) {
                $this->doCallback($this->endpoint, ++$counter, $list->getInfo()->getCount());
                yield $result;
            }
            $list = $this->getNextPage($list);
        }
    }

    /**
     * Get an item by its id.
     *
     * @param  int $id
     * @return ResultInterface
     * @throws ClientException
     */
    public function getById(int $id): ResultInterface
    {
        $options = $this->caching ? [] : ['headers' => [CacheMiddleware::HEADER_RE_VALIDATION => 'yes']];
        $response = $this->httpClient->request('GET', sprintf('%s/%d/', $this->endpoint, $id), $options);
        return $this->createResult($response);
    }

    /**
     * Reset the filters.
     *
     * @return $this
     */
    public function clearFilters(): self
    {
        $this->filters = [];
        return $this;
    }

    /**
     * Apply filters before performing a list() request.
     *
     * @see Client::list()
     *
     * @param  array|string[] $filters
     * @return self
     */
    public function setFilters(array $filters): self
    {
        $this->clearFilters();
        foreach (array_filter($filters) as $name => $value) {
            $this->addFilter($name, $value);
        }
        return $this;
    }

    /**
     * Add a filter.
     *
     * @param  string $name
     * @param  string $value
     * @return $this
     */
    public function addFilter(string $name, string $value): self
    {
        if (!in_array($name, $this->allowedFilters, true)) {
            return $this; // silently ignore invalid filters
        }

        // filterByName() etc. methods
        $method = sprintf('filterBy%s', str_replace(' ', '', ucwords(str_replace('_', ' ', $name))));
        if (method_exists($this, $method)) {
            $this->{$method}($value);
            return $this;
        }

        $this->filters[$name] = $value;
        return $this;
    }

    /**
     * Get currently applied filters
     *
     * @return array|string[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Check if there are currently any filters being applied.
     *
     * @return bool
     */
    public function hasFilters(): bool
    {
        return !empty($this->filters);
    }

    /**
     * Get the next page of a PagedResult.
     *
     * @param  PagedResultInterface $result
     * @return ResultInterface|null
     */
    public function getNextPage(PagedResultInterface $result): ?ResultInterface
    {
        if ($nextPage = $result->getInfo()->getNext()) {
            return $this->load($nextPage);
        }
        return null;
    }

    /**
     * Get the previous page of a PagedResult.
     *
     * @param PagedResultInterface $result
     * @return ResultInterface|null
     */
    public function getPreviousPage(PagedResultInterface $result): ?ResultInterface
    {
        if ($previousPage = $result->getInfo()->getPrev()) {
            return $this->load($previousPage);
        }
        return null;
    }

    /**
     * Load an item from a URL.
     *
     * @param  string $url
     * @return ResultInterface|null
     */
    public function load(string $url): ?ResultInterface
    {
        // extract query params and transform absolute URL to relative URL
        $queryString = parse_url($url, PHP_URL_QUERY) ?: '';
        parse_str($queryString, $query);

        /** @var Uri $baseUri */
        $baseUri = $this->httpClient->getConfig('base_uri');
        $baseUriPath = $baseUri->getPath();
        $path = parse_url($url, PHP_URL_PATH) ?: '';
        $location = str_replace($baseUriPath, '', $path);

        $options = array_merge(
            ['query' => $query],
            $this->caching ? [] : ['headers' => [CacheMiddleware::HEADER_RE_VALIDATION => 'yes']]
        );

        $response = $this->httpClient->request('GET', $location, $options);
        $result = $this->createResult($response);

        // filter "manually" if this was called without query parameters, but filters are set
        // (URL came from another result, e.g. https://rickandmortyapi.com/api/character/38)
        if (empty($query) && !empty($this->filters) && $result instanceof ModelInterface) {
            /** @var string $filterName */
            foreach ($this->filters as $filterName => $filterValue) {
                if (stripos($result->get($filterName), $filterValue) === false) {
                    return null;
                }
            }
        }

        return $result;
    }

    /**
     * Create a PagedResult or ModelInterface object from a Response.
     *
     * @param  ResponseInterface $response
     * @return ResultInterface
     */
    private function createResult(ResponseInterface $response): ResultInterface
    {
        return $this->lastResult = $this->resultFactory->create(json_decode($response->getBody(), true));
    }

    /**
     * Get the result of the last operation.
     *
     * @return ResultInterface|null
     */
    public function getLastResult(): ?ResultInterface
    {
        return $this->lastResult;
    }
}
