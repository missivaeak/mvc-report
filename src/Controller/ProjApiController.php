<?php

namespace App\Controller;

use App\Roadlike\Challenger;
use App\Roadlike\Builder;
use App\Roadlike\Factory;
use App\Roadlike\ORM;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use TypeError;

class ProjApiController extends AbstractController
{
    #[Route("/api/proj/obstacle", name: "api_proj_obstacle", methods: ["GET", "POST", "DELETE"])]
    public function apiProjObstacle(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $orm = new ORM($entityManager);
        $data = [];
        if ($request->getMethod() === "GET") {
            $data = $orm->getAllObstacles();
        } elseif ($request->getMethod() === "POST") {
            $obstacle = [
                'name' => $request->request->get("name"),
                'description' => $request->request->get("description"),
                'difficultyInt' => $request->request->get("difficulty_int"),
                'difficultyStr' => $request->request->get("difficulty_str"),
                'difficultyDex' => $request->request->get("difficulty_dex"),
                'costRewardTime' => $request->request->get("cost_reward_time"),
                'costRewardHealth' => $request->request->get("cost_reward_health"),
                'costRewardStamina' => $request->request->get("cost_reward_stamina"),
                'costRewardInt' => $request->request->get("cost_reward_int"),
                'costRewardStr' => $request->request->get("cost_reward_str"),
                'costRewardDex' => $request->request->get("cost_reward_dex"),
                'costRewardLck' => $request->request->get("cost_reward_lck"),
                'costRewardSpd' => $request->request->get("cost_reward_spd"),
                'costRewardCon' => $request->request->get("cost_reward_con")
                ];
            $data = $orm->addObstacle($obstacle);
        } elseif ($request->getMethod() === "DELETE") {
            $id = $request->request->get('id');
            $data = $orm->delObstacle($id);
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/proj/template", name: "api_proj_template", methods: ["GET", "POST", "DELETE"])]
    public function apiProjTemplate(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $orm = new ORM($entityManager);
        $data = [];
        if ($request->getMethod() === "GET") {
            $data = $orm->getAllTemplates();
        } elseif ($request->getMethod() === "POST") {
            $name = $request->request->get("name");
            $data = $orm->addTemplate($name);
        } elseif ($request->getMethod() === "DELETE") {
            $id = $request->request->get('id');
            $data = $orm->delTemplate($id);
        }

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
