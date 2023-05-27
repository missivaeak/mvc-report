<?php

namespace App\Roadlike;

/**
 * Represents an obstacle to be put into a Road object,
 * can be used with a Challenger object
 */
class Obstacle
{
    /** @var float Luck multiplier */
    private const LUCKMULTIPLIER = 2.0;

    /** @var int Resource cost factor */
    private const COSTFACTOR = 10;

    /** @var string Obstacle name */
    private string $name;

    /** @var string Obstacle description */
    private string $description;

    /** @var array{intelligence?: int, strength?: int, dexterity?: int} Difficulty of the obstacle as an array keyed by stats to test */
    private array $difficulties;

    /** @var array{time: int, health: int, stamina: int, intelligence: int, strength: int, dexterity: int, luck: int, speed: int, constitution: int} Costs and rewards for the obstacle as an array keyed by resource */
    private array $costRewards;

    /**
     * @param string $name Name of the obstacle
     * @param string $description Description of the obstacle
     * @param array{intelligence?: int, strength?: int, dexterity?: int} $difficulties Difficulty for the obstacle
     * @param array{time: int, health: int, stamina: int, intelligence: int, strength: int, dexterity: int, luck: int, speed: int, constitution: int} $costRewards Costs and rewards for the obstacle
     */
    public function __construct(string $name, string $description, array $difficulties, array $costRewards)
    {
        $this->name = $name;
        $this->description = $description;
        $this->difficulties = $difficulties;
        $this->costRewards = $costRewards;
    }

    /** @return string Gets obstacle name */
    public function getName(): string
    {
        return $this->name;
    }

    /** @return string Gets obstacle description */
    public function getDescription(): string
    {
        return $this->description;
    }

    /** @return array{intelligence?: int, strength?: int, dexterity?: int} Gets obstacle difficulty */
    public function getDifficulties(): array
    {
        return $this->difficulties;
    }

    /** @return array{time: int, health: int, stamina: int, intelligence: int, strength: int, dexterity: int, luck: int, speed: int, constitution: int} Gets the costs and rewards for the obstacle */
    public function getCostRewards(): array
    {
        return $this->costRewards;
    }

    /**
     * Attempts the obstacle with a challenger
     * @param Challenger $challenger
     * @return array{lucky: bool, deltas: array{time: int, health: int, stamina: int, intelligence: int, strength: int, dexterity: int, luck: int, speed: int, constitution: int}}
     */
    public function attempt(Challenger $challenger): array
    {
        $stats = $challenger->getStats();
        $difficulties = $this->getDifficulties();
        $costRewards = $this->getCostRewards();
        $lucky = false;

        $intSuccess = $this->testDifficulty("intelligence", $difficulties, $stats);
        $strSuccess = $this->testDifficulty("strength", $difficulties, $stats);
        $dexSuccess = $this->testDifficulty("dexterity", $difficulties, $stats);
        $successes = [$intSuccess, $strSuccess, $dexSuccess];

        if (array_sum($successes) > 0) {
            $lucky = $this->luckChance($stats["luck"]);
        }

        $successFactor = $this->calcSuccessFactor($successes, $lucky);

        $timeDelta = $this->calcResourceDelta($costRewards["time"], $stats, "speed", $successFactor);
        $healthDelta = $this->calcResourceDelta($costRewards["health"], $stats, "", $successFactor);
        $staminaDelta = $this->calcResourceDelta($costRewards["stamina"], $stats, "", $successFactor);
        $intDelta = $this->calcStatDelta($costRewards["intelligence"], $successFactor);
        $strDelta = $this->calcStatDelta($costRewards["strength"], $successFactor);
        $dexDelta = $this->calcStatDelta($costRewards["dexterity"], $successFactor);
        $luckDelta = $this->calcStatDelta($costRewards["luck"], $successFactor);
        $speedDelta = $this->calcStatDelta($costRewards["speed"], $successFactor);
        $conDelta = $this->calcStatDelta($costRewards["constitution"], $successFactor);

        $deltas = [
            "time" => $timeDelta,
            "health" => $healthDelta,
            "stamina" => $staminaDelta,
            "intelligence" => $intDelta,
            "strength" => $strDelta,
            "dexterity" => $dexDelta,
            "luck" => $luckDelta,
            "speed" => $speedDelta,
            "constitution" => $conDelta
        ];

        $result = [
            "deltas" => $deltas,
            "lucky" => $lucky
        ];

        return $result;
    }

    /**
     * Tests the obstacle's difficulty for a given stat
     * @param string $stat
     * @param array{intelligence?: int, strength?: int, dexterity?: int} $difficulties
     * @param array{intelligence: int, strength: int, dexterity: int, speed: int, constitution: int, luck: int} $stats
     * @return float|null Success factor for the test, always positive and non-zero
     */
    protected function testDifficulty(string $stat, array $difficulties, array $stats): ?float
    {
        if (array_key_exists($stat, $difficulties)) {
            // setting difficulty to if it's less than 1, as a sanity check
            $difficulty = $difficulties[$stat] < 1 ? 1 : $difficulties[$stat];
            $statValue = $stats[$stat];
            $factor = $statValue / $difficulty;

            return floatval($factor);
        }

        return null;
    }

    /**
     * Lucky completion, a chance to increase success factor yield
     * @param int $luck Luck value to test with
     * @return bool
     */
    private function luckChance(int $luck): bool
    {
        $randInt = rand(0, 100);
        $lucky = false;

        if ($randInt < $luck) {
            $lucky = true;
        }

        return $lucky;
    }

    /**
     * Calculates an average success factor
     * @param array<float|null> $factors Success factors
     * @param bool $lucky Whether the challenger got lucky
     * @return float
     */
    protected function calcSuccessFactor(array $factors, bool $lucky): float
    {
        $count = 0;
        $sum = 0;
        foreach ($factors as $factor) {
            if ($factor === null) {
                continue;
            }

            $count++;
            $sum += $factor;
        }

        if ($count <= 0) {
            return 1.0;
        }

        $average = $sum / $count;
        $result = log($average + 1.0);

        if ($lucky) {
            $result *= self::LUCKMULTIPLIER;
        }

        return $result;
    }

    /**
     * Calculates time spent to complete the obstacle
     * @param int $resourceCostReward Resource cost value, negative incurs a cost, positive refunds resource
     * @param array{intelligence: int, strength: int, dexterity: int, speed: int, constitution: int, luck: int} $stats The challenger's stats
     * @param string $stat Relevant stat to compare
     * @param float $successFactor A factor affects the result, higher is better for the challenger, must be positive and non-zero
     * @return int
     */
    protected function calcResourceDelta(int $resourceCostReward, array $stats, string $stat, float $successFactor)
    {
        $statValue = $stats[$stat] ?? 50;
        $resourceDelta = $resourceCostReward / $statValue * self::COSTFACTOR;

        if ($resourceDelta < 0) {
            $resourceDelta *= $successFactor;
        } elseif ($resourceDelta > 0) {
            $resourceDelta *= 1 / $successFactor;
        }

        return intval(round($resourceDelta));
    }

    /**
     * Calculates stat change for completing the obstacle
     * @param int $statDelta Stat modificiation value, positive adds and negative subtracts from the stat
     * @param float $successFactor A factor affects the result, higher is better for the challenger, must be positive and non-zero
     * @return int
     */
    protected function calcStatDelta(int $statDelta, float $successFactor)
    {
        if ($statDelta > 0) {
            $statDelta *= $successFactor;
        } elseif ($statDelta < 0) {
            $statDelta *= 1 / $successFactor;
        }

        return intval(round($statDelta));
    }
}
