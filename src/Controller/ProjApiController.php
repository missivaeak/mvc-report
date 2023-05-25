<?php

namespace App\Controller;

use App\Roadlike\Challenger;

use App\Repository\ObstacleRepository;
use App\Entity\Obstacle as ObstacleEntity;
use App\Repository\TemplateRepository;
use App\Entity\Template as TemplateEntity;

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
    #[Route("/api/proj/obstacle", name: "api_proj_obstacle", methods: ["GET", "POST", "DELETE"])]
    public function apiProjObstacle(
        ObstacleRepository $repository,
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $data = [];
        if ($request->getMethod() === "GET") {
            $data = $this->getAllObstacles($repository);
        } elseif ($request->getMethod() === "POST") {
            $data = $this->addObstacle($doctrine, $request);
        } elseif ($request->getMethod() === "DELETE") {
            $data = $this->delObstacle($doctrine, $request, $repository);
        }


        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    private function getAllObstacles(
        ObstacleRepository $repository
    ): array {
        $data = [];
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

        $obstacle = new ObstacleEntity();

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

    private function delObstacle(
        ManagerRegistry $doctrine,
        Request $request,
        ObstacleRepository $repository
    ): array {
        $entityManager = $doctrine->getManager();
        $id = $request->request->get('id');
        $obstacle = $repository->find($id);
        $name = null;

        if (!$obstacle) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $name = $obstacle->getName();
        $entityManager->remove($obstacle);
        $entityManager->flush();

        return ["status" => "success", "obstacle_deleted" => $name];
    }

    #[Route("/api/proj/template", name: "api_proj_template", methods: ["GET", "POST", "DELETE"])]
    public function apiProjTemplate(
        TemplateRepository $repository,
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $data = [];
        if ($request->getMethod() === "GET") {
            $data = self::getAllTemplates($repository);
        } elseif ($request->getMethod() === "POST") {
            $data = $this->addTemplate($doctrine, $request);
        } elseif ($request->getMethod() === "DELETE") {
            $data = $this->delTemplate($doctrine, $request, $repository);
        }


        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    private static function getAllTemplates(
        TemplateRepository $repository
    ): array {
        $data = [];
        $templates = $repository->findAll();
        foreach ($templates as $template) {
            $data[] = [
                'id' => $template->getId() ?? null,
                'name' => $template->getName() ?? null
            ];
        }

        return $data;
    }

    private function addTemplate(
        ManagerRegistry $doctrine,
        Request $request
    ): array {
        $entityManager = $doctrine->getManager();

        $name = $request->request->get("name");

        $template = new TemplateEntity();

        $template->setName($name);

        $entityManager->persist($template);
        $entityManager->flush();

        return ["status" => "success", "template_added" => $name];
    }

    private function delTemplate(
        ManagerRegistry $doctrine,
        Request $request,
        TemplateRepository $repository
    ): array {
        $entityManager = $doctrine->getManager();
        $id = $request->request->get('id');
        $template = $repository->find($id);
        $name = null;

        if (!$template) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $name = $template->getName();
        $entityManager->remove($template);
        $entityManager->flush();

        return ["status" => "success", "template_deleted" => $name];
    }

    #[Route("/api/proj/challenger/selection", name: "api_proj_challenger_selection", methods: ["GET"])]
    public function apiProjChallengerSelection(
        TemplateRepository $repository
    ): Response {
        $data = self::challengerSelection($repository);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    public static function challengerSelection(
        TemplateRepository $repository
    ): array {
        $data = [];
        $templates = self::getAllTemplates($repository);
        $selectionSize = 3;
        $keys = array_rand($templates, $selectionSize);

        foreach ($keys as $key) {
            $name = $templates[$key]["name"];
            $stats = Challenger::randomStatDistribution();
            $data[] = [
                "name" => $name,
                "stats" => $stats
            ];
        }

        return $data;
    }
}
