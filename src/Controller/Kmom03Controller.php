<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Kmom03Controller extends AbstractController
{
    private string $title = "mvc.ades22.game";

    #[Route("/game", name: "game")]
    public function game(): Response
    {
        // if (!$session->get("deck")) {
        //     $session->set("deck", new Deck());
        // }

        return $this->render('pages/game/index.html.twig', [
            'title' => $this->title,
        ]);
    }

    #[Route("/game/start", name: "game_start")]
    public function gameStart(): Response
    {
        // if (!$session->get("deck")) {
        //     $session->set("deck", new Deck());
        // }

        return $this->render('pages/game/start.html.twig', [
            'title' => $this->title,
        ]);
    }

    #[Route("/game/doc", name: "game_doc")]
    public function gameDoc(): Response
    {
        // if (!$session->get("deck")) {
        //     $session->set("deck", new Deck());
        // }

        return $this->render('pages/game/doc.html.twig', [
            'title' => $this->title . ".doc",
        ]);
    }
}
