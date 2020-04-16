<?php


namespace App\Command;

use App\Renderer\RendererInterface;
use App\Service\Search as SearchService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Search extends AbstractCommand
{
    protected const MSG_FILTER_BY_ENDPOINT_PROPERTY = 'filter_by_endpoint_property';
    protected const MSG_SKIPPING_ENDPOINT           = 'skipping_endpoint';
    protected const MSG_GOT_URLS_FROM_ENDPOINT      = 'got_urls_from_endpoint';
    protected const MSG_APPLYING_FILTER_TO_ENDPOINT = 'applying_filter_to_endpoint';

    /**
     * Messages used in this command.
     *
     * @var array<string>>
     */
    protected $messages = [
        self::MSG_FILTER_BY_PROPERTY          => 'Filter items by %s',
        self::MSG_FILTER_BY_ENDPOINT_PROPERTY => 'Filter by %s %s',
        self::MSG_LOADING_ITEMS               => 'Loading items...',
        self::MSG_ITEMS_LOADED                => 'Items loaded',
        self::MSG_SKIPPING_ENDPOINT           => 'Skipping %s endpoint',
        self::MSG_GOT_URLS_FROM_ENDPOINT      => 'Got %d URLs from endpoint',
        self::MSG_LOADING_FROM_ENDPOINT       => 'Loading items from %s endpoint',
        self::MSG_APPLYING_FILTER_TO_ENDPOINT => 'Applying %s filter to %s endpoint',
        self::MSG_DONE                        => 'Done.',
        self::MSG_NO_ITEMS_FOUND              => 'No items found',
        self::MSG_OUTPUT_AS_JSON              => 'Output result(s) as JSON',
    ];

    /**
     * @var SearchService
     */
    protected $search;

    /**
     * Find constructor.
     *
     * @param SearchService     $search
     * @param RendererInterface $renderer
     * @param array<string>     $messages
     */
    public function __construct(SearchService $search, RendererInterface $renderer, array $messages = [])
    {
        $this->search = $search;
        $this->renderer = $renderer;
        parent::__construct($renderer, $messages);
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $this->search->setCallBack([$this, 'progress']);
        $this->search->setCaching(!$input->getOption(self::OPTION_NO_CACHE));

        $values = $this->getOptionValues();

        $items = $this->search->setFilterValues($values)->getItems();

        if (count($items)) {
            $this->renderResults($items);
        } else {
            $this->style->warning($this->getMessage(self::MSG_NO_ITEMS_FOUND));
        }

        return 0;
    }

    /**
     * Create command line options for the command.
     *
     * @return array<InputOption>
     */
    protected function createOptions(): array
    {
        $options = [];
        foreach ($this->search->getAllowedFilters() as $endpoint => $filters) {
            foreach ($filters as $filter) {
                $isMainClient = $this->search->isMainEndpoint($endpoint);
                $options[] = new InputOption(
                    $isMainClient ?
                        $filter :
                        sprintf('%s-%s', $endpoint, $filter),
                    null,
                    InputOption::VALUE_OPTIONAL,
                    $isMainClient ?
                        $this->formatMessage(self::MSG_FILTER_BY_PROPERTY, $filter) :
                        $this->formatMessage(self::MSG_FILTER_BY_ENDPOINT_PROPERTY, $endpoint, $filter)
                );
            }
        }
        return array_merge(parent::createOptions(), $options);
    }

    /**
     * Get the values of the command line options.
     *
     * @return array<string>
     */
    public function getOptionValues(): array
    {
        $result = [];
        foreach ($this->search->getAllowedFilters() as $endpoint => $filters) {
            foreach ($filters as $filter) {
                $isMainClient = $this->search->isMainEndpoint($endpoint);
                $optionName = $isMainClient ? $filter : sprintf('%s-%s', $endpoint, $filter);
                $result[$endpoint][$filter] = $this->input->getOption($optionName);
            }
            $result[$endpoint] = array_filter($result[$endpoint]);
        }

        /** @var array<string> $result */
        $result = array_filter($result);
        return $result;
    }
}
