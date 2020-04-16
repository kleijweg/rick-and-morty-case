<?php


namespace App\Model;

use App\API\ResultInterface;
use App\Traits\GetSetTrait;

/**
 * Class NamedLink
 * @package App\Model
 */
class NamedLink implements ResultInterface
{
    use GetSetTrait;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $url;

    /**
     * NamedLink constructor.
     * @param array<string> $data
     */
    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->name, $this->url);
    }
}
