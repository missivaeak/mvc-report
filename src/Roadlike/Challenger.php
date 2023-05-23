<?php

namespace App\Roadlike;

class Challenger
{
    /** @var int Base health for challengers */
    const BASEHEALTH = 100;

    /** @var int Base stamina for challengers */
    const BASESTAMINA = 100;

    /** @var string Name of the challenger */
    private string $name;

    /** @var array{intelligence: int, strength: int, dexterity: int, speed: int, constitution: int, luck: int} Challenger stats */
    private array $stats;

    /** @var int Current stamina */
    private int $stamina;

    /** @var int Current health */
    private int $health;

    /**
     * @param string $name Name of the challenger
     * @param array{intelligence?: int, strength?: int, dexterity?: int, speed?: int, constitution?: int, luck?: int} $stats Character stats
     */
    public function __construct(string $name, array $stats)
    {
        $this->name = $name;
        $this->stats = [
            "intelligence" => $stats["intelligence"] ?? 0,
            "strength" => $stats["strength"] ?? 0,
            "dexterity" => $stats["dexterity"] ?? 0,
            "speed" => $stats["speed"] ?? 0,
            "constitution" => $stats["constitution"] ?? 0,
            "luck" => $stats["luck"] ?? 0
        ];
        $this->health = $this->stats["constitution"] + self::BASEHEALTH;
        $this->stamina = $this->stats["constitution"] + self::BASESTAMINA;
    }

    /** @return array{intelligence: int, strength: int, dexterity: int, speed: int, constitution: int, luck: int} Get challenger stats */
    public function getStats(): array
    {
        return $this->stats;
    }

    /** @return int Get challenger health */
    public function getHealth(): int
    {
        return $this->health;
    }

    /** @return int Get challenger stamina */
    public function getStamina(): int
    {
        return $this->stamina;
    }

    /** @return string Get challenger name */
    public function getName(): string
    {
        return $this->name;
    }

    /** @return int Get challenger max health */
    public function getMaxHealth(): int
    {
        $constitution = $this->stats["constitution"] ?? 0;
        return $constitution + self::BASEHEALTH;
    }

    /** @return int Get challenger max stamina */
    public function getMaxStamina(): int
    {
        $constitution = $this->stats["constitution"] ?? 0;
        return $constitution + self::BASESTAMINA;
    }

    public function modifyStat(string $stat, int $value): void
    {
        if (array_key_exists($stat, $this->stats)) {
            $this->stats[$stat] += $value;
        }
    }

    public function modifyHealth(int $value): void
    {
        $this->health += $value;
    }

    public function modifyStamina(int $value): void
    {
        $this->stamina += $value;
    }
}