<?php


namespace Kleijweg\RickAndMortyCase\Model;


/**
 * Class Episode
 * @package Kleijweg\RickAndMortyCase\Model
 * @link https://rickandmortyapi.com/documentation/#episode-schema
 * @method int      getId()                     Get the id of the episode
 * @method self     setId(int $id)              Set the id of the episode
 * @method string   getName()                   Get the name of the episode
 * @method self     setName(string $name)       Set the name of the episode
 * @method string   getUrl()                    Get the link to the episode's own endpoint.
 * @method self     setUrl(string $url)         Get the link to the episode's own endpoint.
 * @method string   getCreated()                Get the time at which the episode was created in the database
 * @method self     setCreated(string $created) Set the time at which the episode was created in the database
 */
class Episode extends AbstractModel
{
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
     * Get the air date of the episode
     *
     * @return string
     */
    public function getAirDate(): string
    {
        return $this->air_date;
    }

    /**
     * Set the air date of the episode
     *
     * @param string $air_date
     * @return self
     */
    public function setAirDate(string $air_date): self
    {
        $this->air_date = $air_date;
        return $this;
    }

    /**
     * Get the code of the episode
     *
     * @return string
     */
    public function getEpisode(): string
    {
        return $this->episode;
    }

    /**
     * Set the code of the episode
     *
     * @param string $episode
     * @return self
     */
    public function setEpisode(string $episode): self
    {
        $this->episode = $episode;
        return $this;
    }

    /**
     * Get the list of characters who have been seen in the episode
     *
     * @return array|string[]
     */
    public function getCharacters(): array
    {
        return $this->characters;
    }

    /**
     * Set the list of characters who have been seen in the episode
     *
     * @param array|string[] $characters
     * @return self
     */
    public function setCharacters($characters): self
    {
        $this->characters = $characters;
        return $this;
    }
}
