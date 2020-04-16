<?php


namespace App\Model;

use App\Traits\GetSetTrait;
use JsonSerializable;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class AbstractModel implements ModelInterface, JsonSerializable
{
    use GetSetTrait;

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
     * Time at which the item was created in the database.
     *
     * @var string
     */
    protected $created;

    /**
     * AbstractModel constructor.
     *
     * @param array<mixed> $data
     */
    public function __construct(array $data = [])
    {
        $this->setData($data);
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
     * @param  int $id
     * @return ModelInterface
     */
    public function setId($id): ModelInterface
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
     * @param  string $name
     * @return ModelInterface
     */
    public function setName(string $name): ModelInterface
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
     * @param  string $url
     * @return ModelInterface
     */
    public function setUrl(string $url): ModelInterface
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get the time at which the item was created in the database
     *
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * Set the time at which the item was created in the database
     *
     * @param  string $created
     * @return ModelInterface
     */
    public function setCreated(string $created): ModelInterface
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return array<mixed>
     * @throws ReflectionException
     */
    public function jsonSerialize(): array
    {
        $rc = new ReflectionClass($this);
        $reflectionProperties = $rc->getProperties(ReflectionProperty::IS_PROTECTED);
        $result = [];
        foreach ($reflectionProperties as $property) {
            $propertyName = $property->getName();
            $result[$propertyName] = $this->get($propertyName);
        }
        return $result;
    }
}
