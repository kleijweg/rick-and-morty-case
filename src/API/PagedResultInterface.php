<?php

namespace App\API;

use App\Model\ModelInterface;

/**
 * Class PagedResult
 *
 * @package App\API
 */
interface PagedResultInterface extends ResultInterface
{
    /**
     * @return Info
     */
    public function getInfo(): Info;

    /**
     * @param Info $info
     * @return PagedResult
     */
    public function setInfo(Info $info): PagedResultInterface;

    /**
     * @return ModelInterface[]|array
     */
    public function getResults(): array;
}
