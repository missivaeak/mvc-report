<?php

namespace App\Roadlike;

use App\Roadlike\Road;

/**
 * Represents a crossroads where the player has to choose which way to go, contains multiple Roads
 */
class Crossroads
{
    /** @var int Maximum paths in a crossroads */
    public static int $max = 3;

    /** @var int Minimum paths in a crossroads */
    public static int $min = 1;

    /** @var array<Road> Paths the player can choose between */
    private array $paths;

    /**
     * Creates an empty instance. Add paths with addPath method.
     */
    public function __construct()
    {
        $this->paths = [];
    }

    /**
     * Add a path to the object
     * @param Road $path
     */
    public function addPath(Road $path): void
    {
        $this->paths[] = $path;
    }

    /**
     * Gets an array of all paths
     * @return array<Road>
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * Get a random path
     * @return Road
     */
    public function getRandomPath(): ?Road
    {
        if (!empty($this->paths)) {
            $index = array_rand($this->paths);

            return $this->paths[$index];
        }

        return null;
    }
}
