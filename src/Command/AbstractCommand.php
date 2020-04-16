<?php


namespace App\Command;

use App\API\ResultInterface;
use App\Model\ModelInterface;
use App\Renderer\RendererInterface;
use Generator;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractCommand extends Command
{
    /**
     * Message keys
     */
    protected const MSG_FILTER_BY_PROPERTY    = 'filter_by_property';
    protected const MSG_ITEMS_LOADED          = 'items_loaded';
    protected const MSG_LOADING_ITEMS         = 'loading_items';
    protected const MSG_DONE                  = 'Done.';
    protected const MSG_LOADING_FROM_ENDPOINT = 'loading_from_endpoint';
    protected const MSG_OUTPUT_AS_JSON        = 'output_as_json';
    protected const MSG_NO_ITEMS_FOUND        = 'no_items_found';
    protected const MSG_NO_PROGRESS           = 'no_progress';
    protected const MSG_NO_CACHE              = 'Disable caching';

    /**
     * Progressbar customization
     */
    protected const PROGRESSBAR_FORMAT_NAME = 'custom';
    protected const PROGRESSBAR_FORMAT      = ' %current%/%max% [%bar%] %percent:3s%% %message%';

    /**
     * Options for all commands
     */
    protected const OPTION_JSON        = 'json';
    protected const OPTION_NO_PROGRESS = 'no-progress';
    protected const OPTION_NO_CACHE    = 'no-cache';

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var OutputInterface
     */
    protected $errorOutput;

    /**
     * This style is used for displaying progress bars etc.
     * If JSON output is requested, stderr will be used.
     *
     * @var SymfonyStyle
     */
    protected $style;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var array<string>
     */
    protected $messages = [];
    /**
     * @var array|ProgressBar[]
     */
    protected $progressBars = [];

    /**
     * AbstractCommand constructor.
     *
     * @param RendererInterface $renderer
     * @param array<string>     $messages
     */
    public function __construct(?RendererInterface $renderer, array $messages = [])
    {
        $this->renderer = $renderer;
        $this->setMessages($messages);
        parent::__construct(); // parent construct must be called last because it calls configure()
    }

    /**
     * Configure the command: set up filter options
     */
    public function configure(): void
    {
        $this->setDefinition($this->createOptions());
    }

    /**
     * Progress callback; will be called from SearchService
     *
     * @param string    $identifier
     * @param int       $step
     * @param int       $max
     */
    public function progress(string $identifier, int $step, int $max): void
    {
        if ($this->input->getOption(self::OPTION_NO_PROGRESS)) {
            return;
        }

        if (!isset($this->progressBars[$identifier])) {
            // finish & clear all other progress bars that are still lingering around,
            // there can only be one running at a time
            foreach ($this->progressBars as $key => $progressBar) {
                $progressBar->setMessage($this->getMessage(self::MSG_DONE));
                $progressBar->finish();
                unset($this->progressBars[$key]);
                $this->output->writeln('');
            }
            // create a new progress bar
            $progressBar = $this->style->createProgressBar($max);
            $progressBar->setFormat(self::PROGRESSBAR_FORMAT_NAME);
            $message = $this->formatMessage(self::MSG_LOADING_FROM_ENDPOINT, $identifier);
            $progressBar->setMessage($message);
            $progressBar->display();
            $this->progressBars[$identifier] = $progressBar;
        }

        $progressBar = $this->progressBars[$identifier];
        $progressBar->setMaxSteps($max);
        $progressBar->setProgress($step);

        // are we done?
        if ($step === $max) {
            $progressBar->setMessage($this->getMessage(self::MSG_DONE));
            $progressBar->finish();
            $this->errorOutput->writeln('');
        }
    }

    /**
     * Create command line options for the command.
     *
     * @return array<InputOption>
     */
    protected function createOptions(): array
    {
        return [
            new InputOption(
                self::OPTION_JSON,
                null,
                InputOption::VALUE_NONE,
                $this->getMessage(self::MSG_OUTPUT_AS_JSON)
            ),
            new InputOption(
                self::OPTION_NO_PROGRESS,
                null,
                InputOption::VALUE_NONE,
                $this->getMessage(self::MSG_NO_PROGRESS)
            ),
            new InputOption(
                self::OPTION_NO_CACHE,
                null,
                InputOption::VALUE_NONE,
                $this->getMessage(self::MSG_NO_CACHE)
            ),
        ];
    }

    /**
     * Get a message by its key.
     *
     * @param  string $key
     * @return string
     */
    protected function getMessage(string $key): string
    {
        return $this->messages[$key] ?? $key;
    }

    /**
     * Set messages for the command.
     *
     * @param array<string> $messages
     */
    protected function setMessages(array $messages): void
    {
        foreach ($messages as $key => $message) {
            if (!array_key_exists($key, $this->messages)) {
                throw new InvalidArgumentException(sprintf('Invalid message key [%s]', $key));
            }
            $this->messages[$key] = $message;
        }
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
        $this->input = $input;
        $this->output = $output;
        $errorOutput = $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output;
        $this->errorOutput = $errorOutput;


        $this->style = new SymfonyStyle($input, $input->hasOption(self::OPTION_JSON) ? $errorOutput : $output);
        ProgressBar::setFormatDefinition(self::PROGRESSBAR_FORMAT_NAME, self::PROGRESSBAR_FORMAT);
    }

    /**
     * Format a message with arguments.
     *
     * @param  string $messageKey
     * @param  mixed  ...$args
     * @return string
     */
    protected function formatMessage(string $messageKey, ...$args): string
    {
        $message = $this->getMessage($messageKey);
        return $args ? sprintf($message, ...$args) : $message;
    }

    /**
     * Render the results using the configured renderer or as JSON.
     *
     * @param iterable<ResultInterface>|ResultInterface $results
     */
    protected function renderResults($results): void
    {
        if ($this->input->getOption(self::OPTION_JSON)) {
            /** @var string $encoded */
            $encoded = json_encode(
                $results instanceof Generator ? # no empty JSON object, please
                iterator_to_array($results) :
                $results,
                JSON_PRETTY_PRINT
            );
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('Error encoding JSON');
            }
            $this->output->writeln($encoded);
            return;
        }

        if (is_iterable($results)) {
            foreach ($results as $result) {
                $this->renderer->render($result);
            }
            return;
        }

        if ($results instanceof ModelInterface) {
            $this->renderer->render($results);
            return;
        }
    }
}
