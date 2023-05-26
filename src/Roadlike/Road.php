<?php

namespace App\Roadlike;

use App\Roadlike\Obstacle;

class Road
{
    /** @var int Length of the road */
    private int $length;

    /** @var array<Obstacle> */
    private array $obstacles;

    /**
     * Constructor
     * @param int $length Length of the road
     * @param array<Obstacle> $obstacles Obstacles on the road
     */
    public function __construct(int $length, array $obstacles=[])
    {
        $this->length = $length;
        $this->obstacles = $obstacles;
    }

    /**
     * Gets road length
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * Gets road obstacles
     * @return array<Obstacle>
     */
    public function getObstacles(): array
    {
        return $this->obstacles;
    }

    /**
     * Adds a new obstacle to the road
     * @param Obstacle $obstacle Obstacle to add
     */
    public function addObstacle(Obstacle $obstacle): void
    {
        $this->obstacles[] = $obstacle;
    }

    /**
     * Add length to the road
     * @param int $length Length to add
     */
    public function addLength(int $length): void
    {
        $this->length += $length;
    }
}