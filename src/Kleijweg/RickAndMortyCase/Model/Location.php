<?php


namespace Kleijweg\RickAndMortyCase\Model;


/**
 * Class Location
 * @package Kleijweg\RickAndMortyCase\Model
 * @link https://rickandmortyapi.com/documentation/#location-schema
 * @method int      getId()                     Get the id of the location
 * @method self     setId(int $id)              Set the id of the location
 * @method string   getName()                   Get the name of the location
 * @method self     setName(string $name)       Set the name of the location
 * @method string   getUrl()                    Get the link to the location's own endpoint.
 * @method self     setUrl(string $url)         Set the link to the location's own endpoint.
 * @method string   getCreated()                Get the time at which the location was created in the database
 * @method self     setCreated(string $created) Set the time at which the location was created in the database
 */
class Location extends AbstractModel
{
    /**
     * The type of the location.
     *
     * @var string
     */
    protected $type;

    /**
     * The dimension in which the location is located.
     *
     * @var string
     */
    protected $dimension;

    /**
     * List of character who have been last seen in the location.
     *
     * @var array|string[] (urls)
     */
    protected $residents = [];

    /**
     * Get the type of the location
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the type of the location
     *
     * @param string $type
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get the dimension in which the location is located
     *
     * @return string
     */
    public function getDimension(): string
    {
        return $this->dimension;
    }

    /**
     * Set the dimension in which the location is located
     *
     * @param string $dimension
     * @return self
     */
    public function setDimension(string $dimension): self
    {
        $this->dimension = $dimension;
        return $this;
    }

    /**
     * Get the list of character who have been last seen in the location
     *
     * @return array|string[]
     */
    public function getResidents(): array
    {
        return $this->residents;
    }

    /**
     * Set the list of character who have been last seen in the location
     *
     * @param array|string[] $residents
     * @return self
     */
    public function setResidents($residents): self
    {
        $this->residents = $residents;
        return $this;
    }
}
