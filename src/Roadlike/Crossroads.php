<?php

namespace App\Roadlike;

use App\Roadlike\Road;

class Crossroads
{
    /**
     * Roads the player can choose between
     *  @var array<Road>
     */
    private array $roads;

    /**
     * Creates an empty instance. Add roads with addRoad method.
     */
    public function __construct()
    {
        $this->roads = [];
    }

    /**
     * Add a road to the object
     * @param Road $road
     */
    public function addRoad(Road $road): void
    {
        $this->roads[] = $road;
    }

    /**
     * Gets an array of all roads
     * @return array<Road>
     */
    public function getRoads(): array
    {
        return $this->roads;
    }

    /**
     * Get a random road
     * @return Road
     */
    public function getRandomRoad(): Road
    {
        $index = array_rand($this->roads);

        return $this->roads[$index];
    }
}