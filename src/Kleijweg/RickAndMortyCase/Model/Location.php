<?php


namespace Kleijweg\RickAndMortyCase\Model;


/**
 * Class Location
 * @package Kleijweg\RickAndMortyCase\Model
 * @link https://rickandmortyapi.com/documentation/#location-schema
 */
class Location
{
    /**
     * The id of the location.
     *
     * @var int
     */
    protected $id;

    /**
     * The name of the location.
     *
     * @var string
     */
    protected $name;

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
     * Link to the location's own endpoint.
     *
     * @var string
     */
    protected $url;

    /**
     * Time at which the location was created in the database.
     *
     * @var string
     */
    protected $created;
}