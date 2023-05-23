<?php

namespace App\Controller;

use App\Roadlike\Challenger;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProjController extends AbstractController
{
    #[Route('/proj', name: 'proj_index')]
    public function index(): Response
    {
        return $this->render('proj/index.twig');
    }
    
    #[Route('/proj/game/new', name: 'proj_game_new')]
    public function gameNew(): Response
    {
        return $this->redirectToRoute('proj_game');
    }
    
    #[Route('/proj/game', name: 'proj_game')]
    public function game(): Response
    {
        $challenger = new Challenger("Snick Flames", []);
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
