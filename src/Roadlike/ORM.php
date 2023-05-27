<?php

namespace App\Roadlike;

use App\Entity\Obstacle as ObstacleEntity;
use App\Entity\Template as TemplateEntity;
use App\Entity\Leaderboard as LeaderboardEntity;

use Doctrine\ORM\EntityManagerInterface;

class ORM
{
    /** @var EntityManagerInterface The entity manager */
    private EntityManagerInterface $entityManager;

    /**
     * Constructor
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Gets all templates from the database
     * @return array<array{id: ?int, name: ?string}>
     */
    public function getAllTemplates(): array
    {
        $data = [];
        $repository = $this->entityManager->getRepository(TemplateEntity::class);
        $templates = $repository->findAll();
        foreach ($templates as $template) {
            $data[] = [
                'id' => $template->getId() ?? null,
                'name' => $template->getName() ?? null
            ];
        }

        return $data;
    }

    /**
     * Add a new template to the database
     * @param string $name Name of the template
     * @return array{status: string, template_added: string}
     */
    public function addTemplate(string $name): array
    {

        $template = new TemplateEntity();
        $template->setName($name);
        $this->entityManager->persist($template);
        $this->entityManager->flush();

        return ["status" => "success", "template_added" => $name];
    }

    /**
     * Removes a template from the database
     * @param int $id Id of template to delete
     * @return array{status: string, template_deleted: ?string}
     */
    public function delTemplate(int $id): array
    {
        $repository = $this->entityManager->getRepository(TemplateEntity::class);
        $template = $repository->find($id);

        if (!$template) {
            return ["status" => "failed", "template_deleted" => null];
        }

        $name = $template->getName();
        $this->entityManager->remove($template);
        $this->entityManager->flush();

        return ["status" => "success", "template_deleted" => $name];
    }

    /**
     * Gets all obstacles in the database
     * @return array<array{id: ?int, name: ?string, description: ?string, difficulty_int: ?int, difficulty_str: ?int, difficulty_dex: ?int, cost_reward_time: ?int, cost_reward_health: ?int, cost_reward_stamina: ?int, cost_reward_int: ?int, cost_reward_str: ?int, cost_reward_dex: ?int, cost_reward_lck: ?int, cost_reward_spd: ?int, cost_reward_con: ?int}>
     */
    public function getAllObstacles(): array
    {
        $data = [];
        $repository = $this->entityManager->getRepository(ObstacleEntity::class);
        $obstacles = $repository->findAll();
        foreach ($obstacles as $obstacle) {
            $data[] = [
                'id' => $obstacle->getId() ?? null,
                'name' => $obstacle->getName() ?? null,
                'description' => $obstacle->getDescription() ?? null,
                'difficulty_int' => $obstacle->getDifficultyInt() ?? null,
                'difficulty_str' => $obstacle->getDifficultyStr() ?? null,
                'difficulty_dex' => $obstacle->getDifficultyDex() ?? null,
                'cost_reward_time' => $obstacle->getCostRewardTime() ?? null,
                'cost_reward_health' => $obstacle->getCostRewardHealth() ?? null,
                'cost_reward_stamina' => $obstacle->getCostRewardStamina() ?? null,
                'cost_reward_int' => $obstacle->getCostRewardInt() ?? null,
                'cost_reward_str' => $obstacle->getCostRewardStr() ?? null,
                'cost_reward_dex' => $obstacle->getCostRewardDex() ?? null,
                'cost_reward_lck' => $obstacle->getCostRewardLck() ?? null,
                'cost_reward_spd' => $obstacle->getCostRewardSpd() ?? null,
                'cost_reward_con' => $obstacle->getCostRewardCon() ?? null
            ];
        }

        return $data;
    }

    /**
     * Adds an obstacle to the database
     * @param array{name: string, description: string, difficulty_int: ?int, difficulty_str: ?int, difficulty_dex: ?int, cost_reward_time: int, cost_reward_health: int, cost_reward_stamina: int, cost_reward_int: int, cost_reward_str: int, cost_reward_dex: int, cost_reward_lck: int, cost_reward_spd: int, cost_reward_con: int} $o Array with obstacle data to add
     * @return array{status: string, obstacle_added: string}
     */
    public function addObstacle(array $o): array
    {
        $obstacle = new ObstacleEntity();

        $obstacle->setName($o["name"]);
        $obstacle->setDescription($o["description"]);
        $obstacle->setDifficultyInt($o["difficulty_int"]);
        $obstacle->setDifficultyStr($o["difficulty_str"]);
        $obstacle->setDifficultyDex($o["difficulty_dex"]);
        $obstacle->setCostRewardTime($o["cost_reward_time"]);
        $obstacle->setCostRewardHealth($o["cost_reward_health"]);
        $obstacle->setCostRewardStamina($o["cost_reward_stamina"]);
        $obstacle->setCostRewardInt($o["cost_reward_int"]);
        $obstacle->setCostRewardStr($o["cost_reward_str"]);
        $obstacle->setCostRewardDex($o["cost_reward_dex"]);
        $obstacle->setCostRewardLck($o["cost_reward_lck"]);
        $obstacle->setCostRewardSpd($o["cost_reward_spd"]);
        $obstacle->setCostRewardCon($o["cost_reward_con"]);

        $this->entityManager->persist($obstacle);
        $this->entityManager->flush();

        return ["status" => "success", "obstacle_added" => $o["name"]];
    }

    /**
     * Deletes an obstacle from the database
     * @param int $id Id of the obstacle to delete
     * @return array{status: string, obstacle_deleted: ?string}
     */
    public function delObstacle(int $id): array
    {
        $repository = $this->entityManager->getRepository(ObstacleEntity::class);
        $obstacle = $repository->find($id);

        if (!$obstacle) {
            return ["status" => "failed", "obstacle_deleted" => null];
        }

        $name = $obstacle->getName();
        $this->entityManager->remove($obstacle);
        $this->entityManager->flush();

        return ["status" => "success", "obstacle_deleted" => $name];
    }

    /**
     * Gets all leaderboard entries
     * @return array<array{id: ?int, player: ?string, challenger: ?string, distance: ?int}>
     */
    public function getAllLeaders(): array
    {
        $data = [];
        $repository = $this->entityManager->getRepository(LeaderboardEntity::class);
        $leaders = $repository->findAll();
        foreach ($leaders as $leader) {
            $data[] = [
                'id' => $leader->getId() ?? null,
                'player' => $leader->getPlayer() ?? null,
                'challenger' => $leader->getChallenger() ?? null,
                'distance' => $leader->getDistance() ?? null
            ];
        }

        return $data;
    }

    /**
     * Adds a player to the leaderboard
     * @param array{player: string, challenger: string, distance: int} $p Array with player data to add
     * @return array{status: string, leaderboard_entry_new: string}
     */
    public function addLeader(array $p): array
    {
        $leader = new LeaderboardEntity();

        $leader->setPlayer($p["player"]);
        $leader->setChallenger($p["challenger"]);
        $leader->setDistance($p["distance"]);

        $this->entityManager->persist($leader);
        $this->entityManager->flush();

        return ["status" => "success", "leaderboard_entry_new" => $p["player"]];
    }

    /**
     * Deletes a player from the leaderboard
     * @param int $id Id of the leader to delete
     * @return array{status: string, leaderboard_entry_deleted: ?string}
     */
    public function delLeader(int $id): array
    {
        $repository = $this->entityManager->getRepository(LeaderboardEntity::class);
        $player = $repository->find($id);

        if (!$player) {
            return ["status" => "failed", "leaderboard_entry_deleted" => null];
        }

        $name = $player->getPlayer();
        $this->entityManager->remove($player);
        $this->entityManager->flush();

        return ["status" => "success", "leaderboard_entry_deleted" => $name];
    }

    /**
     * Gets the leaderboards top players sorted
     * in descending order by distance travelled
     * @return array<array{id: ?int, player: ?string, challenger: ?string, distance: ?int}>
     */
    public function getLeaderboard(): array
    {
        $leaders = $this->getAllLeaders();
        usort($leaders, function ($a, $b) {
            return $b["distance"] <=> $a["distance"];
        });

        return array_slice($leaders, 0, 10);
    }
}
