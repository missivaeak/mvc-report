<?php

namespace App\Controller;

use App\Roadlike\Factory;
use App\Roadlike\ORM;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ProjApiController extends AbstractController
{
    #[Route("/api/proj/obstacle", name: "api_proj_obstacle_get", methods: ["GET"])]
    public function apiProjObstacleGet(
        EntityManagerInterface $entityManager
    ): Response {
        $orm = new ORM($entityManager);
        $data = $orm->getAllObstacles();

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/proj/obstacle", name: "api_proj_obstacle_post", methods: ["POST"])]
    public function apiProjObstaclePost(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $orm = new ORM($entityManager);
        $obstacle = $this->arrangeObstaclePostData($request);
        $data = $orm->addObstacle($obstacle);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/proj/obstacle", name: "api_proj_obstacle_delete", methods: ["DELETE"])]
    public function apiProjObstacleDelete(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $orm = new ORM($entityManager);
        $id = intval($request->request->get('id'));
        $data = $orm->delObstacle($id);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    /**
     * @return array{name: string, description: string, difficulty_int: ?int, difficulty_str: ?int, difficulty_dex: ?int, cost_reward_time: int, cost_reward_health: int, cost_reward_stamina: int, cost_reward_int: int, cost_reward_str: int, cost_reward_dex: int, cost_reward_lck: int, cost_reward_spd: int, cost_reward_con: int}
     */
    private function arrangeObstaclePostData(Request $request)
    {
        $difficultyInt = $request->request->get("difficulty_int");
        $difficultyInt = $difficultyInt === null ? null : intval($difficultyInt);
        $difficultyStr = $request->request->get("difficulty_str");
        $difficultyStr = $difficultyStr === null ? null : intval($difficultyStr);
        $difficultyDex = $request->request->get("difficulty_dex");
        $difficultyDex = $difficultyDex === null ? null : intval($difficultyInt);
        $data = [
            'name' => strval($request->request->get("name")),
            'description' => strval($request->request->get("description")),
            'difficulty_int' => $difficultyInt,
            'difficulty_str' => $difficultyStr,
            'difficulty_dex' => $difficultyDex,
            'cost_reward_time' => intval($request->request->get("cost_reward_time")),
            'cost_reward_health' => intval($request->request->get("cost_reward_health")),
            'cost_reward_stamina' => intval($request->request->get("cost_reward_stamina")),
            'cost_reward_int' => intval($request->request->get("cost_reward_int")),
            'cost_reward_str' => intval($request->request->get("cost_reward_str")),
            'cost_reward_dex' => intval($request->request->get("cost_reward_dex")),
            'cost_reward_lck' => intval($request->request->get("cost_reward_lck")),
            'cost_reward_spd' => intval($request->request->get("cost_reward_spd")),
            'cost_reward_con' => intval($request->request->get("cost_reward_con"))
            ];

        return $data;
    }

    #[Route("/api/proj/template", name: "api_proj_template_get", methods: ["GET"])]
    public function apiProjTemplateGet(
        EntityManagerInterface $entityManager,
    ): Response {
        $orm = new ORM($entityManager);
        $data = $orm->getAllTemplates();
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/proj/template", name: "api_proj_template_post", methods: ["POST"])]
    public function apiProjTemplatePost(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $orm = new ORM($entityManager);
        $name = strval($request->request->get("name"));
        $data = $orm->addTemplate($name);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/proj/template", name: "api_proj_template_delete", methods: ["DELETE"])]
    public function apiProjTemplateDelete(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $orm = new ORM($entityManager);
        $id = intval($request->request->get('id'));
        $data = $orm->delTemplate($id);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/proj/leaderboard", name: "api_proj_leaderboard_get", methods: ["GET"])]
    public function apiProjLeaderboardGet(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $orm = new ORM($entityManager);
        $data = [];

        if ($request->query->get('top10') === "true" ) {
            $data = $orm->getLeaderboard();
        } else {
            $data = $orm->getAllLeaders();
        } 

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/proj/leaderboard", name: "api_proj_leaderboard_post", methods: ["POST"])]
    public function apiProjLeaderboardPost(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $orm = new ORM($entityManager);
        $leader = [
            "player" => strval($request->request->get("player")),
            "challenger" => strval($request->request->get("challenger")),
            "distance" => intval($request->request->get("distance"))
        ];
        $data = $orm->addLeader($leader);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/proj/leaderboard", name: "api_proj_leaderboard_delete", methods: ["DELETE"])]
    public function apiProjLeaderboardDelete(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $orm = new ORM($entityManager);
        $id = intval($request->request->get('id'));
        $data = $orm->delLeader($id);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/proj/draft", name: "api_proj_draft", methods: ["GET"])]
    public function apiProjDraft(
        EntityManagerInterface $entityManager
    ): Response {
        $factory = new Factory();
        $orm = new ORM($entityManager);
        $templates = $orm->getAllTemplates();
        $draft = $factory->buildDraft($templates, 3);
        $data = [
            [
                "name" => $draft[0]->getName(),
                "stats" => $draft[0]->getStats()
            ],
            [
                "name" => $draft[1]->getName(),
                "stats" => $draft[1]->getStats()
            ],
            [
                "name" => $draft[2]->getName(),
                "stats" => $draft[2]->getStats()
            ],
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
