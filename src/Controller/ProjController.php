<?php

namespace App\Controller;

use App\Roadlike\Challenger;

use App\Repository\TemplateRepository;

use App\Controller\ProjApiController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProjController extends AbstractController
{
    #[Route('/proj', name: 'proj_index')]
    public function index(): Response
    {
        return $this->render('proj/index.twig');
    }

    #[Route('/proj/game/new', name: 'proj_game_new', methods: ["POST", "GET"])]
    public function gameNew(
        TemplateRepository $templateRepository,
        Request $request,
        SessionInterface $session
    ): Response {
        if ($request->getMethod() === "GET") {
            $selection = ProjApiController::challengerSelection($templateRepository);

            return $this->render('proj/selection.twig', [
                'selection' => $selection
            ]);
        } elseif ($request->getMethod() === "POST") {
            $name = $request->request->get("name");
            $stats = [
                "intelligence" => $request->request->get("intelligence"),
                "strength" => $request->request->get("strength"),
                "dexterity" => $request->request->get("dexterity"),
                "luck" => $request->request->get("luck"),
                "speed" => $request->request->get("speed"),
                "constitution" => $request->request->get("constitution")
            ];

            $session->set("challenger", new Challenger($name, $stats));

            return $this->redirectToRoute('proj_game');
        }
    }

    // #[Route('/proj/game/new', name: 'proj_game_new')]
    // public function gameNew(): Response
    // {
    //     return $this->redirectToRoute('proj_game');
    // }

    #[Route('/proj/game', name: 'proj_game')]
    public function game(SessionInterface $session): Response
    {
        $challenger = $session->get('challenger') ?? null;
        return $this->render('proj/game.twig', [
            "challenger" => $challenger
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
