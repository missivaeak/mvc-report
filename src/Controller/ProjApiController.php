<?php

namespace App\Controller;

// use App\Card\Deck;
// use App\Card\Hand;

use App\Repository\ObstacleRepository;
use App\Entity\Obstacle;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use TypeError;

class ProjApiController extends AbstractController
{
    #[Route("/api/proj/obstacle", name: "api_proj_obstacle", methods: ["GET", "POST"])]
    public function apiProjObstaclesGet(
        ObstacleRepository $obstacleRepository,
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $data = [];
        if ($request->getMethod() === "GET") {
            $data = $this->getAllObstacles($obstacleRepository);
        } elseif ($request->getMethod() === "POST") {
            $data = $this->addObstacle($doctrine, $request);
        }


        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    private function getAllObstacles(
        ObstacleRepository $obstacleRepository
    ): array {
        $data = [];
        $obstacles = $obstacleRepository->findAll();
        foreach ($obstacles as $obstacle) {
            $data[] = [
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
    
    private function addObstacle(
        ManagerRegistry $doctrine,
        Request $request
    ): array {
        $entityManager = $doctrine->getManager();

        $name = $request->request->get("name");
        $description = $request->request->get("description");
        $difficultyInt = $request->request->get("difficulty_int");
        $difficultyStr = $request->request->get("difficulty_str");
        $difficultyDex = $request->request->get("difficulty_dex");
        $costRewardTime = $request->request->get("cost_reward_time");
        $costRewardHealth = $request->request->get("cost_reward_health");
        $costRewardStamina = $request->request->get("cost_reward_stamina");
        $costRewardInt = $request->request->get("cost_reward_int");
        $costRewardStr = $request->request->get("cost_reward_str");
        $costRewardDex = $request->request->get("cost_reward_dex");
        $costRewardLck = $request->request->get("cost_reward_lck");
        $costRewardSpd = $request->request->get("cost_reward_spd");
        $costRewardCon = $request->request->get("cost_reward_con");

        $obstacle = new Obstacle();

        $obstacle->setName($name);
        $obstacle->setDescription($description);
        $obstacle->setDifficultyInt($difficultyInt);
        $obstacle->setDifficultyStr($difficultyStr);
        $obstacle->setDifficultyDex($difficultyDex);
        $obstacle->setCostRewardTime($costRewardTime);
        $obstacle->setCostRewardHealth($costRewardHealth);
        $obstacle->setCostRewardStamina($costRewardStamina);
        $obstacle->setCostRewardInt($costRewardInt);
        $obstacle->setCostRewardStr($costRewardStr);
        $obstacle->setCostRewardDex($costRewardDex);
        $obstacle->setCostRewardLck($costRewardLck);
        $obstacle->setCostRewardSpd($costRewardSpd);
        $obstacle->setCostRewardCon($costRewardCon);

        $entityManager->persist($obstacle);
        $entityManager->flush();

        return ["status" => "success", "obstacle_added" => $name];
    }
}
