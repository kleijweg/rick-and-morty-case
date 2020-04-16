<?php


namespace App\Traits;

use App\API\ResultInterface;
use InvalidArgumentException;

/**
 * Generic getter/setter functionality
 *
 * Trait GetSetTrait
 *
 * @package App\Traits
 */
trait GetSetTrait
{
    /**
     * @param  array<mixed> $data
     * @return ResultInterface
     */
    public function setData(array $data): ResultInterface
    {
        foreach ($data as $name => $value) {
            $this->set($name, $value);
        }
        return $this;
    }

    /**
     * Generic setter
     *
     * @param string $name
     * @param mixed $value
     * @return ResultInterface
     */
    public function set(string $name, $value): ResultInterface
    {
        $setter = sprintf('set%s', str_replace(' ', '', ucwords(str_replace('_', ' ', $name))));
        if (method_exists($this, $setter)) {
            return $this->{$setter}($value);
        }

        if (property_exists($this, $name)) {
            $this->{$name} = $value;
            return $this;
        }

        throw new InvalidArgumentException(sprintf('Unknown property "%s"', $name));
    }

    /**
     * Magic setter
     *
     * @param        string $name
     * @param        mixed $value
     * @return       ResultInterface
     * @noinspection MagicMethodsValidityInspection
     */
    public function __set(string $name, $value)
    {
        return $this->set($name, $value);
    }

    /**
     * Generic getter
     *
     * @param  string $name
     * @return mixed
     */
    public function get(string $name)
    {
        $getter = sprintf('get%s', str_replace(' ', '', ucwords(str_replace('_', ' ', $name))));
        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        }
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
        throw new InvalidArgumentException(sprintf('Unknown property "%s"', $name));
    }

    /**
     * Magic getter
     *
     * @param  string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }
}
