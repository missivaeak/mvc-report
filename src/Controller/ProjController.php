<?php

namespace App\Controller;

use App\Roadlike\Challenger;
use App\Roadlike\Manager;
use App\Roadlike\Road;
use App\Roadlike\Factory;
use App\Roadlike\ORM;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProjController extends AbstractController
{
    #[Route('/proj', name: 'proj_index')]
    public function index(): Response
    {
        return $this->render('proj/index.twig');
    }

    #[Route('/proj/game/new', name: 'proj_game_new', methods: ["POST", "GET"])]
    public function gameNew(
        EntityManagerInterface $entityManager,
        Request $request,
        SessionInterface $session
    ): Response {
        $factory = new Factory();
        $orm = new ORM($entityManager);

        if ($request->getMethod() === "GET") {
            $templates = $orm->getAllTemplates();
            $draft = $factory->buildDraft($templates, 3);
            $session->set("draft", $draft);

            return $this->render('proj/draft.twig', [
                'draft' => $draft
            ]);
        } elseif ($request->getMethod() === "POST") {
            $index = $request->request->get("index");
            $draft = $session->get("draft") ?? null;

            if ($draft) {
                $challenger = $draft[$index];
                $game = $factory->buildManager($challenger);
                $session->set("game", $game);
            }

            return $this->redirectToRoute('proj_game_next');
        }

        return $this->redirectToRoute('proj_index');
    }

    #[Route('/proj/game/next', name: 'proj_game_next')]
    public function gameNext(
        EntityManagerInterface $entityManager,
        Request $request,
        SessionInterface $session
    ): Response {
        $orm = new ORM($entityManager);
        $factory = new Factory();
        $game = $session->get("game");

        if ($game->isGameOver()) {
            return $this->redirectToRoute('proj_game_end');
        }

        if ($game && $game->getCrossroads()) {
            // there is a crossroads so next is travelling
            $index = intval($request->query->get('index'));
            $paths = $game->getCrossroads()->getPaths();
            $travelPath = $paths[$index];
            $game->unsetCrossroads();

            // travelling results
            $challenger = $game->getChallenger();
            $obstacles = $travelPath->getObstacles();
            $results = [];
            foreach ($obstacles as $obstacle) {
                $results[] = $obstacle->attempt($challenger);
            }

            // resolve travelling
            $distance = $travelPath->getLength();
            $game->getJourney()->addLength($distance);
            $response = [];
            foreach ($results as $result) {
                $response[] = $game->resolveAttempt($result);
            }

            $session->set('travelResult', $response);
            $session->set("travelPath", $travelPath);

            return $this->redirectToRoute('proj_game');
        } elseif ($game) {
            // no crossroads, next is a crossroads
            $obstacleData = $orm->getAllObstacles();
            $crossroads = $factory->buildCrossroads($obstacleData, 2, 3);
            $game->setCrossroads($crossroads);

            $session->remove('travelPath');
            $session->remove('travelResult');

            return $this->redirectToRoute('proj_game');
        }

        return $this->redirectToRoute('proj_index');
    }

    #[Route('/proj/game/end', name: 'proj_game_end', methods: ["GET", "POST"])]
    public function gameEnd(
        EntityManagerInterface $entityManager,
        Request $request,
        SessionInterface $session
    ): Response {
        $game = $session->get('game');

        if (!$game) {
            return $this->redirectToRoute('proj_index');
        }

        if ($request->getMethod() === "POST") {
            $orm = new ORM($entityManager);
            $data = [
                "player" => strval($request->request->get('player')),
                "challenger" => strval($game->getChallenger()->getName()),
                "distance" => intval($game->getJourney()->getLength())
            ];
            $orm->addLeader($data);
            $session->remove('game');
            return $this->redirectToRoute('proj_leaderboard');
        }

        return $this->render('proj/end.twig', [
            "game" => $game
        ]);
    }

    #[Route('/proj/game', name: 'proj_game')]
    public function game(
        SessionInterface $session
    ): Response {
        $game = $session->get('game') ?? null;
        $travelPath = $session->get('travelPath') ?? null;
        $travelResult = $session->get('travelResult') ?? null;

        if (!$game) {
            return $this->redirectToRoute('proj_index');
        } elseif (!$travelPath) {
            return $this->render('proj/game.twig', [
                "game" => $game
            ]);
        }

        if (!$travelResult) {
        }

        return $this->render('proj/game.twig', [
            "game" => $game
        ]);
    }

    #[Route('/proj/leaderboard', name: 'proj_leaderboard')]
    public function leaderboard(EntityManagerInterface $entityManager): Response
    {
        $orm = new ORM($entityManager);
        $leaderboard = $orm->getLeaderboard();
        return $this->render('proj/leaderboard.twig', [
            "leaderboard" => $leaderboard
        ]);
    }

    #[Route('/proj/about', name: 'proj_about')]
    public function about(): Response
    {
        return $this->render('proj/about.twig');
    }

    #[Route('/proj/api', name: 'proj_api')]
    public function api(): Response
    {
        return $this->render('proj/api.twig');
    }

    #[Route('/proj/about/database', name: 'proj_about_database')]
    public function aboutDatabase(): Response
    {
        return $this->render('proj/database.twig');
    }
}
