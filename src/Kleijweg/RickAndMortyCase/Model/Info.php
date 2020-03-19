<?php


namespace Kleijweg\RickAndMortyCase\Model;


/**
 * Class Info
 * @package Kleijweg\RickAndMortyCase\Model
 * @link https://rickandmortyapi.com/documentation/#info-and-pagination
 */
class Info
{
    /**
     * The length of the response
     *
     * @var int
     */
    protected $count;

    /**
     * The amount of pages
     *
     * @var int
     */
    protected $pages;

    /**
     * Link to the next page (if it exists)
     *
     * @var string
     */
    protected $next;

    /**
     * Link to the previous page (if it exists)
     *
     * @var string
     */
    protected $prev;

    /**
     * Get the length of the response
     *
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Set the length of the response
     *
     * @param int $count
     * @return self
     */
    public function setCount(int $count): self
    {
        $this->count = $count;
        return $this;
    }

    /**
     * Get the amount of pages
     *
     * @return int
     */
    public function getPages(): int
    {
        return $this->pages;
    }

    /**
     * Set the amount of pages
     *
     * @param int $pages
     * @return self
     */
    public function setPages(int $pages): self
    {
        $this->pages = $pages;
        return $this;
    }

    /**
     * Get the link to the next page (if it exists)
     *
     * @return string
     */
    public function getNext(): string
    {
        return $this->next;
    }

    /**
     * Set the link to the next page
     *
     * @param string $next
     * @return self
     */
    public function setNext(string $next): self
    {
        $this->next = $next;
        return $this;
    }

    /**
     * Get the link to the previous page (if it exists)
     *
     * @return string
     */
    public function getPrev(): string
    {
        return $this->prev;
    }

    /**
     * Set the link to the previous page
     *
     * @param string $prev
     * @return self
     */
    public function setPrev(string $prev): self
    {
        $this->prev = $prev;
        return $this;
    }
}
