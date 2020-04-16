<?php

namespace App\API;

/**
 * Class PagedResult
 *
 * @package App\API
 */
interface ResultInterface
{
    /**
     * @param array<mixed> $data
     * @return ResultInterface
     */
    public function setData(array $data): ResultInterface;

    /**
     * Generic setter
     *
     * @param string $name
     * @param mixed $value
     * @return ResultInterface
     */
    public function set(string $name, $value): ResultInterface;

    /**
     * Generic getter
     *
     * @param string $name
     * @return mixed
     */
    public function get(string $name);
}
