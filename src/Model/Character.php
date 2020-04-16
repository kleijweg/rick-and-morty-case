<?php


namespace App\Model;

use InvalidArgumentException;

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
     * Valid statuses
     */
    public const AVAILABLE_STATUSES = [
        self::STATUS_UNKNOWN,
        self::STATUS_ALIVE,
        self::STATUS_DEAD,
    ];

    /**
     * Valid genders
     */
    public const AVAILABLE_GENDERS = [
        self::GENDER_UNKNOWN,
        self::GENDER_FEMALE,
        self::GENDER_MALE,
        self::GENDER_GENDERLESS,
    ];

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
     * @var NamedLink|null
     */
    protected $origin;

    /**
     * Name and link to the character's last known location endpoint.
     *
     * @var NamedLink|null
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
     * @param  string $status
     * @return self
     */
    public function setStatus(string $status): self
    {
        if (!in_array($status, self::AVAILABLE_STATUSES, true)) {
            throw new InvalidArgumentException(sprintf('Invalid status "%s"', $status));
        }
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
     * @param  string $species
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
     * @param  string $type
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
     * @param  string $gender
     * @return self
     */
    public function setGender(string $gender): self
    {
        if (!in_array($gender, self::AVAILABLE_GENDERS, true)) {
            throw new InvalidArgumentException(sprintf('Invalid gender "%s"', $gender));
        }
        $this->gender = $gender;
        return $this;
    }

    /**
     * Get the name and link to the character's origin location
     *
     * @return NamedLink|null
     */
    public function getOrigin(): ?NamedLink
    {
        return $this->origin;
    }

    /**
     * Set the name and link to the character's origin location
     *
     * @param  NamedLink|array<string> $origin
     * @return self
     */
    public function setOrigin($origin): self
    {
        $this->origin = $origin instanceof NamedLink ? $origin : new NamedLink($origin);
        return $this;
    }

    /**
     * Get the name and link to the character's last known location endpoint
     *
     * @return NamedLink|null
     */
    public function getLocation(): ?NamedLink
    {
        return $this->location;
    }

    /**
     * Set the name and link to the character's last known location endpoint
     *
     * @param  NamedLink|array<string> $location
     * @return self
     */
    public function setLocation($location): self
    {
        $this->location = $location instanceof NamedLink ? $location: new NamedLink($location);
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
     * @param  string $image
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
     * @param  array|string[] $episode
     * @return self
     */
    public function setEpisode(array $episode): self
    {
        $this->episode = $episode;
        return $this;
    }
}
