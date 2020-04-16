<?php


namespace App\Command;

use League\Flysystem\Adapter\Local;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * Class ClearCache
 * Clears the Guzzle cache
 *
 * @package App\Command
 */
class ClearCache extends AbstractCommand
{
    /**
     * @var Local
     */
    protected $adapter;

    /**
     * ClearCache constructor.
     *
     * @param Local $adapter
     */
    public function __construct(Local $adapter)
    {
        $this->adapter = $adapter;
        parent::__construct(null, []);
    }

    /**
     * Execute the command: clear the cache
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        parent::execute($input, $output);
        try {
            $pathPrefix = $this->adapter->getPathPrefix();
            if (empty($pathPrefix)) {
                throw new RuntimeException('No root configured');
            }
            $this->adapter->deleteDir('');
        } catch (Throwable $e) {
            $this->errorOutput->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return 1;
        }

        return 0;
    }
}
