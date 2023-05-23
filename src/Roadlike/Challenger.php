<?php

namespace App\Roadlike;

class Challenger
{
    private int $intelligence;
    private int $strength;
    private int $dexterity;
    private int $speed;
    private int $constitution;
    private int $luck;
    private int $stamina;
    private int $health;

    public function __construct($stats)
    {
        $this->intelligence = $stats["intelligence"] ?? 0;
        $this->strength = $stats["strength"] ?? 0;
        $this->dexterity = $stats["dexterity"] ?? 0;
        $this->speed = $stats["speed"] ?? 0;
        $this->constitution = $stats["constitution"] ?? 0;
        $this->luck = $stat["luck"] ?? 0;
        $this->health = $this->constitution + 100;
        $this->stamina = $this->constitution + 100;
    }

    public function getStats(): array
    {
        $stats = [
            "intelligence" => $this->intelligence,
            "strength" => $this->strength,
            "dexterity" =>$this->dexterity,
            "speed" => $this->speed,
            "constitution" => $this->constitution,
            "luck" => $this->luck
        ];

        return $stats;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function getStamina(): int
    {
        return $this->stamina;
    }
}