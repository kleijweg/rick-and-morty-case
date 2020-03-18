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
}