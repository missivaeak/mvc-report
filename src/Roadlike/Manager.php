<?php

namespace App\Roadlike;

use App\Roadlike\Challenger;
use App\Roadlike\Road;
use App\Roadlike\Crossroads;

class Manager
{
    /** @var int Start time */
    private int $startingTime;

    /** @var Challenger The player's challenger */
    private Challenger $challenger;

    /** @var Road The completed journey */
    private Road $journey;

    /** @var Crossroads The next crossroads to choose from */
    private Crossroads $crossroads;

    /** @var int Time remaining */
    private int $time;

    /**
     * Constructor
     * @param Challenger $challenger Player's challenger
     * @param Road $road Completed road
     * @param int $time Start time, defaults to class constant value
     */
    public function __construct(
        Challenger $challenger,
        Road $journey, 
        int $time=500
    ) {
        $this->challenger = $challenger;
        $this->journey = $journey;
        $this->time = $time;
        $this->startingTime = $time;
    }

    /**
     * Gets challenger
     */
    public function getChallenger(): Challenger
    {
        return $this->challenger;
    }

    /**
     * Gets journey
     */
    public function getJourney(): Road
    {
        return $this->journey;
    }

    /**
     * Gets crossroads
     */
    public function getCrossroads(): ?Crossroads
    {
        if (isset($this->crossroads)) {
            return $this->crossroads;
        }

        return null;
    }

    /**
     * Gets time remaining
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * Gets starting time
     */
    public function getStartingTime(): int
    {
        return $this->startingTime;
    }

    /**
     * Set a new crossroads
     */
    public function setCrossroads(Crossroads $crossroads): void
    {
        $this->crossroads = $crossroads;
    }

    /**
     * Unset crossroads
     */
    public function unsetCrossroads(): void
    {
        unset($this->crossroads);
    }

    /**
     * Modify time remaining
     * @param int $time
     */
    public function modifyTime(int $time): void
    {
        $this->time += $time;
    }
}