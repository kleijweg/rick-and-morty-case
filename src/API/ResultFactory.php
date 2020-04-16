<?php


namespace App\API;

use App\Model\ModelInterface;

/**
 * Class ResultFactory
 *
 * @package App\API
 */
class ResultFactory
{
    /**
     * Class of the model(s) to create. Must implement ModelInterface.
     *
     * @var string
     */
    protected $modelClass;

    /**
     * PagedResultFactory constructor.
     *
     * @param string $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * Create a PagedResult or item object from data
     *
     * @param  array<mixed> $data
     * @return ResultInterface
     */
    public function create(array $data): ResultInterface
    {
        return isset($data['info']) ? $this->createPagedResult($data) : $this->createModel($data);
    }

    /**
     * Create a PagedResult object.
     *
     * @param  array<mixed> $data
     * @return PagedResult
     */
    public function createPagedResult(array $data): PagedResultInterface
    {
        return new PagedResult(
            [
            'info'    => new Info($data['info'] ?? []),
            'results' => array_map([$this, 'createModel'], $data['results'] ?? []),
            ]
        );
    }

    /**
     * Create an item.
     *
     * @param array<mixed> $data
     * @return ModelInterface
     */
    public function createModel(array $data): ModelInterface
    {
        return new $this->modelClass($data);
    }
}
