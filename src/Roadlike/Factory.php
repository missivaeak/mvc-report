<?php

namespace App\Roadlike;

use App\Roadlike\Challenger;
use App\Roadlike\Manager;
use App\Roadlike\Road;
use App\Roadlike\Crossroads;

use App\Controller\ProjApiController;

class Factory
{
    /**
     * Builds a manager
     *
     * @param Challenger $challenger A challenger object to participate in the game
     * @return Manager
     */
    public function buildManager(Challenger $challenger): Manager
    {
        $journey = new Road(0);
        $game = new Manager($challenger, $journey);

        return $game;
    }

    /**
     * Builds a draft of challengers
     *
     * @param array<array{name: ?string}> $templates Templates from the database
     * @param int $draftSize Amount of challengers in the draft
     * @return array<Challenger>
     */
    public function buildDraft(array $templates, int $draftSize): array
    {
        $draft = [];
        $keys = array_rand($templates, $draftSize);

        foreach ($keys as $key) {
            $name = $templates[$key]["name"];
            $stats = $this->randomStatDistribution();
            $draft[] = new Challenger($name, $stats);
        }

        return $draft;
    }

    /**
     * Returns random obstacle objects from an array of obstacle data
     *
     * @param array{id: ?int, name: ?int, description: ?int, difficulty_int: ?int, difficulty_str: ?int, difficulty_dex: ?int, cost_reward_time: ?int, cost_reward_health: ?int, cost_reward_stamina: ?int, cost_reward_int: ?int, cost_reward_str: ?int, cost_reward_dex: ?int, cost_reward_lck: ?int, cost_reward_spd: ?int, cost_reward_con: ?int} $obstacleData Obstacle data from the database
     * @param int $amount Amount of obstacles to generate
     * @return array<Obstacle>
     */
    public function buildObstacles(array $obstaclesData, int $amount): array
    {
        $obstacles = [];
        $keys = array_rand($obstaclesData, $amount);

        foreach ($keys as $key) {
            $obstacle = $obstaclesData[$key];
            $name = $obstacle["name"];
            $description = $obstacle["description"];
            $difficulties = [
                'intelligence' => $obstacle['difficulty_int'],
                'strength' => $obstacle['difficulty_str'],
                'dexterity' => $obstacle['difficulty_dex']
            ];
            $costRewards = [
                'time' => $obstacle['cost_reward_time'],
                'health' => $obstacle['cost_reward_health'],
                'stamina' => $obstacle['cost_reward_stamina'],
                'intelligence' => $obstacle['cost_reward_int'],
                'strength' => $obstacle['cost_reward_str'],
                'dexterity' => $obstacle['cost_reward_dex'],
                'luck' => $obstacle['cost_reward_lck'],
                'speed' => $obstacle['cost_reward_spd'],
                'constitution' => $obstacle['cost_reward_con']
            ];
            $obstacles[] = new Obstacle($name, $description, $difficulties, $costRewards);
        }

        return $obstacles;
    }

    /**
     * Builds a crossroads
     *
     * @param array{id: ?int, name: ?int, description: ?int, difficulty_int: ?int, difficulty_str: ?int, difficulty_dex: ?int, cost_reward_time: ?int, cost_reward_health: ?int, cost_reward_stamina: ?int, cost_reward_int: ?int, cost_reward_str: ?int, cost_reward_dex: ?int, cost_reward_lck: ?int, cost_reward_spd: ?int, cost_reward_con: ?int} $repository ORM Obstacle repository
     * @param int $min Minimum number of paths
     * @param int $max Maximum number of paths
     * @return Crossroads
     */
    public function buildCrossroads(array $obstacleData, int $min, int $max): Crossroads
    {
        $pathsAmount = rand($min, $max);
        $crossroads = new Crossroads();

        for ($i = 0; $i < $pathsAmount; $i++) {
            $shape = $this->randomRoadShape(2, 3);
            $path = new Road($shape["length"]);
            $obstacles = $this->buildObstacles($obstacleData, $shape["obstacles"]);
            foreach ($obstacles as $obstacle) {
                $path->addObstacle($obstacle);
            }
            $crossroads->addPath($path);
        }

        return $crossroads;
    }

    /**
     * Gets a random shape for a road
     *
     * @param int $min Minimum number of obstacles
     * @param int $max Max number of obstacles
     * @return array{length: int, obstacles: int}
     */
    private function randomRoadShape(int $min, int $max): array
    {
        $obstacles = rand($min, $max);
        $length = 0;

        for ($i = 0; $i < $obstacles; $i++) {
            $length += rand(25, 75);
        }

        $shape = [
            "length" => $length,
            "obstacles" => $obstacles
        ];

        return $shape;
    }

    /**
     * Returns a random stat distribution
     *
     * @return array{intelligence: int, strength: int, dexterity: int, speed: int, constitution: int, luck: int}
     */
    private function randomStatDistribution(): array
    {
        // pool of points to distribute
        $pointPool = 200;

        // starting point
        $stats = [
            "intelligence" => 0,
            "strength" => 0,
            "dexterity" => 0,
            "speed" => 0,
            "constitution" => 0,
            "luck" => 0
        ];

        // create a random stat profile
        $percents = [0.2, 0.15, 0.1, 0.07, 0.04];
        foreach ($percents as $percent) {
            $points = intval(round($pointPool * $percent));
            $pointPool -= $points;
            $stat = array_rand($stats);
            $stats[$stat] += $points;
        }

        // distribute remaining points randomly
        while ($pointPool > 0) {
            $stat = array_rand($stats);
            $stats[$stat] += 1;
            $pointPool -= 1;
        }

        return $stats;
    }
}
