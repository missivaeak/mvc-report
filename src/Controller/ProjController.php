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

            return $this->redirectToRoute('proj_game');
        }

        return $this->redirectToRoute('proj_index');
    }

    // #[Route('/proj/game/new', name: 'proj_game_new')]
    // public function gameNew(): Response
    // {
    //     return $this->redirectToRoute('proj_game');
    // }

    #[Route('/proj/game', name: 'proj_game')]
    public function game(SessionInterface $session): Response
    {
        $game = $session->get('game') ?? null;

        if (!$game) {
            return $this->redirectToRoute('proj_index');
        }

        return $this->render('proj/game.twig', [
            "game" => $game
        ]);
    }

    #[Route('/proj/leaderboard', name: 'proj_leaderboard')]
    public function leaderboard(): Response
    {
        return $this->render('proj/leaderboard.twig');
    }

    #[Route('/proj/about', name: 'proj_about')]
    public function about(): Response
    {
        return $this->render('proj/about.twig');
    }
}
