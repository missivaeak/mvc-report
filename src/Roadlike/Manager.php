<?php

namespace App\Roadlike;

use App\Roadlike\Challenger;
use App\Roadlike\Road;
use App\Roadlike\Crossroads;

class Manager
{
    /** @var int Base time */
    public const BASETIME = 100;

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
     * @param Road $journey Completed road
     * @param int $time Start time, defaults to class constant value
     */
    public function __construct(
        Challenger $challenger,
        Road $journey,
        int $time=self::BASETIME
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
        if ($this->time < 0) {
            $this->time = 0;
        }
    }

    /**
     * Checks if the game is over
     */
    public function isGameOver(): bool
    {
        if (
            $this->getTime() <= 0
            || $this->challenger->getHealth() <= 0
            || $this->challenger->getStamina() <= 0
        ) {
            return true;
        }

        return false;
    }

    /**
     * Resolves an obstacle attempt
     * @param array{lucky: bool, deltas: array{time: int, health: int, stamina: int, intelligence: int, strength: int, dexterity: int, luck: int, speed: int, constitution: int}} $result Result data from the attempt
     * @return array<string>
     */
    public function resolveAttempt(array $result): array
    {
        $deltas = $result["deltas"];

        foreach ($deltas as $stat => $delta) {
            switch ($stat) {
                case "time":
                    $this->modifyTime($delta);
                    break;
                case "health":
                    $this->challenger->modifyHealth($delta);
                    break;
                case "stamina":
                    $this->challenger->modifyStamina($delta);
                    break;
                case "constitution":
                    $this->challenger->modifyStat($stat, $delta);
                    $this->challenger->modifyHealth($delta);
                    $this->challenger->modifyStamina($delta);
                    break;
                default:
                    $this->challenger->modifyStat($stat, $delta);
                    break;
            }
        }

        $response = $this->buildResponse($result);

        return $response;
    }

    /**
     * Makes an array of strings for the obstacle attempt
     * @param array{lucky: bool, deltas: array{time: int, health: int, stamina: int, intelligence: int, strength: int, dexterity: int, luck: int, speed: int, constitution: int}} $result Result data from the attempt
     * @return array<String>
     */
    private function buildResponse(array $result): array
    {
        $response = [];
        $deltas = $result["deltas"];
        $resources = ["time", "health", "stamina"];
        $stats = ["intelligence", "strength", "dexterity", "luck", "speed", "constitution"];

        if ($result["lucky"]) {
            $response[] = "Du hade tur!";
        }

        foreach ($resources as $resource) {
            $delta = $deltas[$resource];
            $string = $this->buildResourceString($resource, $delta);
            if ($string) {
                $response[] = $string;
            }
        }

        foreach ($stats as $stat) {
            $delta = $deltas[$stat];
            $string = $this->buildStatString($stat, $delta);
            if ($string) {
                $response[] = $string;
            }
        }

        return $response;
    }

    /**
     * Makes a string out of a resources change
     * @param string $resource Name of the resource
     * @param int $delta Delta of the change
     * @return string
     */
    private function buildResourceString(string $resource, int $delta): ?string
    {
        if ($this->sign($delta) === 0) {
            return null;
        }
        $string = "Du ";
        switch ($this->sign($delta)) {
            case -1:
                $string .= "förlorade ";
                break;
            case 1:
                $string .= "fick ";
                break;
        }
        $string .= strval(abs($delta)) . " ";
        switch ($resource) {
            case "time":
                $string .= "tid.";
                break;
            case "health":
                $string .= "hälsa.";
                break;
            case "stamina":
                $string .= "energi.";
                break;
        }

        return $string;
    }

    /**
     * Makes a string out of a stat change
     * @param string $stat Name of the resource
     * @param int $delta Delta of the change
     * @return string
     */
    private function buildStatString(string $stat, int $delta): ?string
    {
        if ($this->sign($delta) === 0) {
            return null;
        }
        $string = "";
        switch ($stat) {
            case "intelligence":
                $string .= "Intelligens ";
                break;
            case "strength":
                $string .= "Styrka ";
                break;
            case "dexterity":
                $string .= "Smidighet ";
                break;
            case "luck":
                $string .= "Tur ";
                break;
            case "speed":
                $string .= "Hastighet ";
                break;
            case "constitution":
                $string .= "Uthållighet ";
                break;
        }
        switch ($this->sign($delta)) {
            case -1:
                $string .= "minskade med ";
                break;
            case 1:
                $string .= "ökade med ";
                break;
        }
        $string .= strval(abs($delta)) . " poäng.";
        return $string;
    }

    /**
     * Get the sign for a number
     * @param int $number Number to check
     * @return int returns -1, 0 or 1
     */
    private function sign($number)
    {
        return ($number > 0) - ($number < 0);
    }
}
