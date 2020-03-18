<?php


namespace Kleijweg\RickAndMortyCase\Model;


/**
 * Class Episode
 * @package Kleijweg\RickAndMortyCase\Model
 * @link https://rickandmortyapi.com/documentation/#episode-schema
 */
class Episode
{
    /**
     * The id of the episode.
     *
     * @var int
     */
    protected $id;

    /**
     * The name of the episode.
     *
     * @var string
     */
    protected $name;

    /**
     * The air date of the episode.
     *
     * @var string
     */
    protected $air_date;

    /**
     * The code of the episode.
     *
     * @var string
     */
    protected $episode;

    /**
     * List of characters who have been seen in the episode.
     *
     * @var array|string[] (urls)
     */
    protected $characters = [];

    /**
     * Link to the episode's own endpoint.
     *
     * @var string
     */
    protected $url;

    /**
     * Time at which the episode was created in the database.
     *
     * @var string
     */
    protected $created;
}