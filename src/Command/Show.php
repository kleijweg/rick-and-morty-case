<?php


namespace App\Command;

use App\API\Client;
use App\Renderer\RendererInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class Show extends AbstractCommand
{
    public const ARGUMENT_ID = 'id';

    /**
     * @var Client
     */
    protected $client;

    protected $messages = [
        self::MSG_OUTPUT_AS_JSON => 'Output item as JSON',
        self::MSG_NO_PROGRESS    => 'Not applicable',
    ];

    /**
     * Show constructor.
     *
     * @param Client            $client
     * @param RendererInterface $renderer
     * @param array<string>     $messages
     */
    public function __construct(Client $client, RendererInterface $renderer, array $messages = [])
    {
        $this->client = $client;
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
        try {
            /** @var string|null $id */
            $id = $input->getArgument(self::ARGUMENT_ID);

            $this->client->setCaching(!$input->getOption(self::OPTION_NO_CACHE));

            $result = $this->client->getById((int)$id);
            $this->renderResults($result);
//            $this->renderer->render($result);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            if ($response instanceof ResponseInterface) {
                $this->style->error(sprintf('%s %s', $response->getStatusCode(), $response->getReasonPhrase()));
            } else {
                $this->style->error(sprintf('Client error: %s', $e->getMessage()));
            }
        } catch (Throwable $e) {
            $this->style->error($e->getMessage());
        }

        return 0;
    }
}
