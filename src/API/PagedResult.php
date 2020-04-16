<?php


namespace App\API;

use App\Model\ModelInterface;
use App\Traits\GetSetTrait;
use Countable;

/**
 * Class PagedResult
 *
 * @package App\API
 */
class PagedResult implements Countable, PagedResultInterface
{
    use GetSetTrait;

    /**
     * @var Info|null
     */
    protected $info;

    /**
     * @var array|ModelInterface[]
     */
    protected $results = [];

    /**
     * PagedResult constructor.
     *
     * @param array<mixed> $data
     */
    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    /**
     * @return Info
     */
    public function getInfo(): Info
    {
        return $this->info;
    }

    /**
     * @param  Info $info
     * @return PagedResult
     */
    public function setInfo(Info $info): PagedResultInterface
    {
        $this->info = $info;
        return $this;
    }

    /**
     * @return ModelInterface[]|array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @param  ModelInterface[]|array $results
     * @return PagedResult
     */
    public function setResults(array $results): PagedResultInterface
    {
        $this->results = $results;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->info instanceof Info ? $this->info->getCount() : 0;
    }
}
