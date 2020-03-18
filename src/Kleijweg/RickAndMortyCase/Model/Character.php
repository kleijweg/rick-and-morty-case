<?php


namespace Kleijweg\RickAndMortyCase\Model;


/**
 * Class Character
 * @package Kleijweg\RickAndMortyCase\Model
 * @link https://rickandmortyapi.com/documentation/#character-schema
 */
class Character
{
    /**
     * The id of the character.
     *
     * @var int
     */
    protected $id;

    /**
     * The name of the character.
     *
     * @var string
     */
    protected $name;

    /**
     * The status of the character ('Alive', 'Dead' or 'unknown').
     *
     * @var string
     */
    protected $status;

    /**
     * The species of the character.
     *
     * @var string
     */
    protected $species;

    /**
     * The type or subspecies of the character.
     *
     * @var string
     */
    protected $type;

    /**
     * The gender of the character ('Female', 'Male', 'Genderless' or 'unknown').
     *
     * @var string
     */
    protected $gender;

    /**
     * Name and link to the character's origin location.
     *
     * @var object TODO
     */
    protected $origin;

    /**
     * Name and link to the character's last known location endpoint.
     *
     * @var object
     */
    protected $location;

    /**
     * Link to the character's image. All images are 300x300px and most are medium shots or portraits since they are
     * intended to be used as avatars.
     *
     * @var string (URL)
     */
    protected $image;

    /**
     * List of episodes in which this character appeared.
     *
     * @var array|string[] (urls)
     */
    protected $episode = [];

    /**
     * Link to the character's own URL endpoint.
     *
     * @var string
     */
    protected $url;

    /**
     * Time at which the character was created in the database.
     *
     * @var string
     */
    protected $created;
}