<?php

namespace App\Controller;

use App\Game\GinRummyHand;
use App\Game\StandardPlayingCard;
use App\Game\StandardPlayingCardsDeck;

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
        $deck = new StandardPlayingCardsDeck();
        foreach ($deck->getAllValidCardValues() as $validCard) {
            $deck->add(new StandardPlayingCard(
                $validCard["suit"],
                $validCard["value"]
            ));
        }
        $deck->shuffle();

        $hand = new GinRummyHand();
        for ($i = 0; $i < 10; $i++) {
            $hand->add($deck->draw());
        }
        $hand->addMeld();
        // $hand->addToMeld(9, 0);

        return $this->render('pages/game/start.html.twig', [
            'title' => $this->title,
            'deck' => $deck,
            'hand' => $hand
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
