<?php


namespace Kleijweg\RickAndMortyCase\Model;


/**
 * Class AbstractModel
 * @package Kleijweg\RickAndMortyCase\Model
 */
abstract class AbstractModel
{
    /**
     * The id of the model.
     *
     * @var int
     */
    protected $id;

    /**
     * The name of the model.
     *
     * @var string;
     */
    protected $name;

    /**
     * Link to the model's own URL endpoint.
     *
     * @var string
     */
    protected $url;

    /**
     * Time at which the record was created in the database.
     *
     * @var string
     */
    protected $created;

    /**
     * Generic getter
     *
     * @param string $name
     * @return mixed
     */
    public function get(string $name)
    {
        $getter = sprintf('get%s', ucfirst($name));
        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        }
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
        throw new \InvalidArgumentException('Unknown property');
    }

    /**
     * Generic setter
     *
     * @param string $name
     * @param mixed $value
     * @return $this|mixed
     */
    public function set(string $name, $value)
    {
        $setter = sprintf('set%s', ucfirst($name));
        if (method_exists($this, $setter)) {
            return $this->{$setter}($value);
        }
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
            return $this;
        }
        throw new \InvalidArgumentException('Unknown property');
    }

    /**
     * Magic getter
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Magic setter
     *
     * @param $name
     * @param $value
     * @return $this|mixed
     * @noinspection MagicMethodsValidityInspection
     */
    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    /**
     * Get the id of the model
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the id of the model
     *
     * @param int $id
     * @return self
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the name of the model
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the model
     *
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the link to the model's own URL endpoint
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set the link to the model's own URL endpoint
     *
     * @param string $url
     * @return self
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get the time at which the record was created in the database
     *
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * Set the time at which the record was created in the database
     *
     * @param string $created
     * @return self
     */
    public function setCreated(string $created): self
    {
        $this->created = $created;
        return $this;
    }
}
