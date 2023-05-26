<?php

namespace App\Roadlike;

use App\Entity\Obstacle as ObstacleEntity;
use App\Entity\Template as TemplateEntity;

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
     * @return array{id: int?, name: string?}
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
     * @return array{status: string, template_deleted: string}
     */
    public function delTemplate(int $id): array
    {
        $repository = $this->entityManager->getRepository(TemplateEntity::class);
        $template = $repository->find($id);

        if (!$template) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $name = $template->getName();
        $this->entityManager->remove($template);
        $this->entityManager->flush();

        return ["status" => "success", "template_deleted" => $name];
    }

    /**
     * Gets all obstacles in the database
     * @return array<array{id: int?, name: int?, description: int?, difficulty_int: int?, difficulty_str: int?, difficulty_dex: int?, cost_reward_time: int?, cost_reward_health: int?, cost_reward_stamina: int?, cost_reward_int: int?, cost_reward_str: int?, cost_reward_dex: int?, cost_reward_lck: int?, cost_reward_spd: int?, cost_reward_con: int?}>
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
     * @param array{name: int?, description: int?, difficulty_int: int?, difficulty_str: int?, difficulty_dex: int?, cost_reward_time: int?, cost_reward_health: int?, cost_reward_stamina: int?, cost_reward_int: int?, cost_reward_str: int?, cost_reward_dex: int?, cost_reward_lck: int?, cost_reward_spd: int?, cost_reward_con: int?} $o Array with obstacle data to add
     * @return array{status: string, obstacle_added: string}
     */
    public function addObstacle(array $o): array {
        $obstacle = new ObstacleEntity();

        $obstacle->setName($o["name"]);
        $obstacle->setDescription($o["description"]);
        $obstacle->setDifficultyInt($o["difficultyInt"]);
        $obstacle->setDifficultyStr($o["difficultyStr"]);
        $obstacle->setDifficultyDex($o["difficultyDex"]);
        $obstacle->setCostRewardTime($o["costRewardTime"]);
        $obstacle->setCostRewardHealth($o["costRewardHealth"]);
        $obstacle->setCostRewardStamina($o["costRewardStamina"]);
        $obstacle->setCostRewardInt($o["costRewardInt"]);
        $obstacle->setCostRewardStr($o["costRewardStr"]);
        $obstacle->setCostRewardDex($o["costRewardDex"]);
        $obstacle->setCostRewardLck($o["costRewardLck"]);
        $obstacle->setCostRewardSpd($o["costRewardSpd"]);
        $obstacle->setCostRewardCon($o["costRewardCon"]);

        $this->entityManager->persist($obstacle);
        $this->entityManager->flush();

        return ["status" => "success", "obstacle_added" => $o["name"]];
    }

    /**
     * Deletes an obstacle from the database
     * @param int $id Id of the obstacle to delete
     * @return array{status: string, obstacle_deleted: string?}
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
}