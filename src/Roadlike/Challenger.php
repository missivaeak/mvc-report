<?php

namespace App\Roadlike;

class Challenger
{
    /** @var int Base health for challengers */
    public const BASEHEALTH = 100;

    /** @var int Base stamina for challengers */
    public const BASESTAMINA = 100;

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

    /**
     * Get challenger stats
     * @return array{intelligence: int, strength: int, dexterity: int, speed: int, constitution: int, luck: int}
     */
    public function getStats(): array
    {
        return $this->stats;
    }

    /**
     * Get challenger health
     * @return int
     */
    public function getHealth(): int
    {
        return $this->health;
    }

    /**
     * Get challenger stamina
     * @return int
     */
    public function getStamina(): int
    {
        return $this->stamina;
    }

    /**
     * Get challenger name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get challenger max health
     * @return int
     */
    public function getMaxHealth(): int
    {
        $constitution = $this->stats["constitution"] ?? 0;
        return $constitution + self::BASEHEALTH;
    }

    /**
     * Get challenger max stamina
     * @return int
     */
    public function getMaxStamina(): int
    {
        $constitution = $this->stats["constitution"] ?? 0;
        return $constitution + self::BASESTAMINA;
    }

    /**
     * Modifies a stat
     * @param string $stat Name of the stat to modify
     * @param int $value Value to modify the stat by
     */
    public function modifyStat(string $stat, int $value): void
    {
        if (array_key_exists($stat, $this->stats)) {
            $this->stats[$stat] += $value;
        }
    }

    /**
     * Modifies health
     * @param int $value Value to modify health by
     */
    public function modifyHealth(int $value): void
    {
        $this->health += $value;
        if ($this->health > $this->getMaxHealth()) {
            $this->health = $this->getMaxHealth();
        }
    }

    /**
     * Modifies stamina
     * @param int $value Value to modify stamina by
     */
    public function modifyStamina(int $value): void
    {
        $this->stamina += $value;
        if ($this->stamina > $this->getMaxStamina()) {
            $this->stamina = $this->getMaxStamina();
        }
    }
}