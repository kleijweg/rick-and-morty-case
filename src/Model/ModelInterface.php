<?php


namespace App\Model;

use App\API\ResultInterface;

interface ModelInterface extends ResultInterface
{
    public function set(string $name, $value): ResultInterface;

    public function get(string $name);
}
