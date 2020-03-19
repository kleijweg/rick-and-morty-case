<?php


namespace Kleijweg\RickAndMortyCase\Model;


/**
 * Class Character
 * @package Kleijweg\RickAndMortyCase\Model
 * @link https://rickandmortyapi.com/documentation/#character-schema
 * @method int      getId()                     Get the id of the character
 * @method self     setId(int $id)              Set the id of the character
 * @method string   getName()                   Get the name of the character
 * @method self     setName(string $name)       Set the name of the character
 * @method string   getUrl()                    Get the link to the character's own URL endpoint
 * @method self     setUrl(string $url)         Set the link to the character's own URL endpoint
 * @method string   getCreated()                Get the time at which the character was created in the database
 * @method self     setCreated(string $created) Set the time at which the character was created in the database
 */
class Character extends AbstractModel
{
    public const STATUS_UNKNOWN    = 'unknown';
    public const STATUS_ALIVE      = 'Alive';
    public const STATUS_DEAD       = 'Dead';
    public const GENDER_UNKNOWN    = 'unknown';
    public const GENDER_FEMALE     = 'Female';
    public const GENDER_MALE       = 'Male';
    public const GENDER_GENDERLESS = 'Genderless';

    /**
     * The status of the character ('Alive', 'Dead' or 'unknown').
     *
     * @var string
     */
    protected $status = self::STATUS_UNKNOWN;

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
    protected $gender = self::GENDER_UNKNOWN;

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
     * Get the status of the character ('Alive', 'Dead' or 'unknown')
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the status of the character ('Alive', 'Dead' or 'unknown')
     *
     * @param string $status
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the species of the character
     *
     * @return string
     */
    public function getSpecies(): string
    {
        return $this->species;
    }

    /**
     * Set the species of the character
     *
     * @param string $species
     * @return self
     */
    public function setSpecies(string $species): self
    {
        $this->species = $species;
        return $this;
    }

    /**
     * Get the type or subspecies of the character
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the type or subspecies of the character
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
     * Get the gender of the character ('Female', 'Male', 'Genderless' or 'unknown')
     *
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * Set the gender of the character ('Female', 'Male', 'Genderless' or 'unknown')
     *
     * @param string $gender
     * @return self
     */
    public function setGender(string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Get the name and link to the character's origin location
     *
     * @return object
     */
    public function getOrigin(): object
    {
        return $this->origin;
    }

    /**
     * Set the name and link to the character's origin location
     *
     * @param object $origin
     * @return self
     */
    public function setOrigin(object $origin): self
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * Get the name and link to the character's last known location endpoint
     *
     * @return object
     */
    public function getLocation(): object
    {
        return $this->location;
    }

    /**
     * Set the name and link to the character's last known location endpoint
     *
     * @param object $location
     * @return self
     */
    public function setLocation(object $location): self
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Get the link to the character's image
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Set the link to the character's image
     *
     * @param string $image
     * @return self
     */
    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get the list of episodes in which this character appeared
     *
     * @return array|string[]
     */
    public function getEpisode(): array
    {
        return $this->episode;
    }

    /**
     * Set the list of episodes in which this character appeared
     *
     * @param array|string[] $episode
     * @return self
     */
    public function setEpisode(array $episode): self
    {
        $this->episode = $episode;
        return $this;
    }
}
