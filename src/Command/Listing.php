<?php


namespace App\Command;

use App\API\Client;
use App\API\PagedResultInterface;
use App\Model\ModelInterface;
use App\Renderer\RendererInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Listing
 * Lists characters, episodes and locations
 *
 * @package App\Command
 */
class Listing extends AbstractCommand
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Default messages for command
     *
     * @var array<string>
     */
    protected $messages = [
        self::MSG_FILTER_BY_PROPERTY    => 'Filter items by %s',
        self::MSG_LOADING_ITEMS         => 'Loading items',
        self::MSG_ITEMS_LOADED          => 'All items loaded',
        self::MSG_LOADING_FROM_ENDPOINT => 'Loading items from %s endpoint',
        self::MSG_NO_ITEMS_FOUND        => 'No items found',
        self::MSG_OUTPUT_AS_JSON        => 'Output result(s) as JSON',
    ];

    /**
     * Listing constructor.
     *
     * @param Client            $client
     * @param RendererInterface $renderer
     * @param array<string>     $messages
     */
    public function __construct(Client $client, RendererInterface $renderer, $messages = [])
    {
        $this->client = $client;
        parent::__construct($renderer, $messages); // parent construct must be called last because it calls configure()
    }

    /**
     * Create command line options for the command.
     *
     * @return array<InputOption>
     */
    protected function createOptions(): array
    {
        $options = [];
        foreach ($this->client->getAllowedFilters() as $name) {
            $options[] = new InputOption(
                $name,
                null,
                InputOption::VALUE_OPTIONAL,
                sprintf($this->getMessage(self::MSG_FILTER_BY_PROPERTY), $name)
            );
        }
        return array_merge(parent::createOptions(), $options);
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->client->setCaching(!$input->getOption(self::OPTION_NO_CACHE));

        $this->client->setCallBack([$this, 'progress']);

        // apply the filters to the client
        $this->client->setFilters($this->getOptionValues());

        // fetch the results and render them
        $items = $this->getItems();
        $this->renderResults($items);

        return 0;
    }

    /**
     * Get all items from the client while displaying a fancy progress bar.
     *
     * @return array<ModelInterface>
     */
    private function getItems(): array
    {
        $items = [];
        $counter = 0;
        foreach ($this->client->getAll() as $item) {
            $lastResult = $this->client->getLastResult();
            $count = $lastResult instanceof PagedResultInterface ? $lastResult->getInfo()->getCount() : null;
            $this->progress($this->client->getEndpoint(), ++$counter, $count);
            $items[] = $item;
        }
        return $items;
    }

    /**
     * Get the option values from the command line.
     *
     * @return array<string>
     */
    private function getOptionValues(): array
    {
        $result = [];
        foreach ($this->getDefinition()->getOptions() as $inputOption) {
            $name = $inputOption->getName();
            $result[$name] = $this->input->getOption($name);
        }
        /** @var array<string> $result */
        $result = array_filter($result);
        return $result;
    }
}
