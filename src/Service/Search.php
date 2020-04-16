<?php


namespace App\Service;

use App\API\Client;
use App\API\PagedResult;
use App\API\ResultInterface;
use InvalidArgumentException;
use RuntimeException;

class Search
{
    /**
     * TODO
     * Search mode, used when searching in multiple clients/endpoints.
     *
     * AND: results must exist in all clients
     * OR: results from all clients are merged
     *
     * NB: filters in a single client always use AND (server side)
     */
    const MODE_AND = 'AND';
    const MODE_OR  = 'OR';

    const ALLOWED_MODES = [self::MODE_AND, self::MODE_OR];

    /**
     * @var string
     */
    protected $mode = self::MODE_AND;

    /**
     * @var array|Client[]
     */
    protected $clients;

    /**
     * @var callable
     */
    private $callback;

    /**
     * @var array<string>
     */
    private $propertyMapping;

    /**
     * @var array<mixed>
     */
    private $filterValues;

    /**
     * Search constructor.
     *
     * @param Client[]|array $clients
     * @param array<string>  $propertyMapping
     */
    public function __construct($clients, array $propertyMapping)
    {
        $this->clients = $clients;
        $this->propertyMapping = $propertyMapping;
    }

    /**
     * Set the search mode
     *
     * @param  string $mode
     * @return $this
     */
    public function setMode(string $mode): self
    {
        if (!in_array($mode, self::ALLOWED_MODES, true)) {
            throw new InvalidArgumentException(sprintf('Invalid mode "%s"', $mode));
        }
        $this->mode = $mode;
        return $this;
    }

    /**
     * Get the allowed filters, grouped by endpoint
     *
     * @return array<array<string>>
     */
    public function getAllowedFilters(): array
    {
        $result = [];
        foreach ($this->clients as $client) {
            $result[$client->getEndpoint()] = $client->getAllowedFilters();
        }
        return $result;
    }

    /**
     * Fetch the results from the API and return the items
     *
     * @return array<ResultInterface>
     */
    public function getItems(): array
    {
        $this->applyFilterValues();

        $mainClient = $this->getMainClient();
        // if there are only filters for the main client, or none at all, skip the other clients
        if (empty($this->filterValues) ||
            (count($this->filterValues) === 1 && isset($this->filterValues[$mainClient->getEndpoint()]))) {
            $mainClient->setCallBack($this->callback);
            return iterator_to_array($mainClient->getAll());
        }

        $urls = $this->getItemUrls() ?? [];
        $items = [];

        $counter = 0; // fore keeping track of progress
        $total = count($urls);
        foreach ($urls as $url) {
            $client = $this->getClientByUrl($url);
            $item = $client->load($url);
            $items[] = $item;
            $this->doCallback($client->getEndpoint(), ++$counter, $total);
        }
        return array_values(array_filter($items));
    }

    /**
     * Enable/disable caching
     *
     * @param bool $caching
     * @return $this
     */
    public function setCaching(bool $caching): self
    {
        foreach ($this->clients as $client) {
            $client->setCaching($caching);
        }
        return $this;
    }

    /**
     * Get URLS from all clients except the main client
     * In the property mapping the property that leads to the items is defined
     *
     * @return array<string>|null
     */
    private function getItemUrls(): ?array
    {
        $urls = null;
        foreach ($this->clients as $client) {
            $property = $this->propertyMapping[$client->getEndpoint()] ?? null;

            if ($property === null || $this->isMainClient($client) || !$client->hasFilters()) {
                continue;
            }

            $urls = $this->combineUrls($urls, $this->getUrlsFromClient($client, $property));
        }
        return $urls;
    }

    /**
     * @param  array<string>|null $urls
     * @param  array<string>|null $urlsFromClient
     * @return array<string>|null
     */
    private function combineUrls(?array $urls, ?array $urlsFromClient): ?array
    {
        switch (strtoupper($this->mode)) {
            case 'OR':
                return array_unique(array_merge($urls, $urlsFromClient));
            case 'AND':
            default:
                if ($urls === null/* && !empty($urlsFromClient)*/) {
                    return $urlsFromClient;
                }

                if ($urlsFromClient !== null) {
                    return array_intersect($urls, $urlsFromClient);
                }
                break;
        }
        return $urls;
    }

    /**
     * Get URLS from a client
     *
     * @param  Client $client
     * @param  string $property
     * @return array<string>
     */
    private function getUrlsFromClient(Client $client, string $property): array
    {
        $urls = [];
        $counter = 0;
        foreach ($client->getAll() as $result) {
            $lastResult = $client->getLastResult();
            if ($lastResult instanceof PagedResult) {
                $this->doCallback($client->getEndpoint(), ++$counter, $lastResult->getInfo()->getCount());
            }
            foreach ($result->get($property) as $url) {
                $urls[] = $url;
            }
        }

        return $urls;
    }

    /**
     * @param  array<mixed> $values
     * @return $this
     */
    public function setFilterValues(array $values): self
    {
        $this->filterValues = $values;
        return $this;
    }

    /**
     * @return $this
     */
    private function applyFilterValues(): self
    {
        foreach ($this->filterValues as $endpoint => $filterValues) {
            $this->getClientByEndpoint($endpoint)->setFilters($filterValues);
        }
        return $this;
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
     * @param  string $endpoint
     * @return bool
     */
    public function isMainEndpoint(string $endpoint): bool
    {
        $mainClient = $this->getMainClient();
        return $mainClient instanceof Client && $endpoint === $mainClient->getEndpoint();
    }

    /**
     * @param  Client $client
     * @return bool
     */
    private function isMainClient(Client $client): bool
    {
        $mainClient = $this->getMainClient();
        return $mainClient instanceof Client && $client === $mainClient;
    }

    /**
     * Get the main client (the result we're looking for)
     *
     * @return Client
     * @throws RuntimeException if client not found
     */
    public function getMainClient(): Client
    {
        $client = reset($this->clients);
        if (!$client instanceof Client) {
            throw new RuntimeException('Client not found');
        }
        return $client;
    }

    /**
     * @param  string $endpoint
     * @return Client
     */
    private function getClientByEndpoint(string $endpoint): Client
    {
        foreach ($this->clients as $client) {
            if ($client->getEndpoint() === $endpoint) {
                return $client;
            }
        }
        throw new RuntimeException(sprintf('No client found for endpoint %s', $endpoint));
    }

    /**
     * @param  string $url
     * @return Client|mixed
     */
    private function getClientByUrl(string $url)
    {
        foreach ($this->clients as $client) {
            $endpoint = $client->getEndpoint();
            // TODO regex
            if (strpos($url, $endpoint) !== false) {
                return $client;
            }
        }
        throw new RuntimeException(sprintf('No client found for URL %s', $url));
    }
}
