<?php

namespace App\Controller;

use App\Game\GinRummyHand;
use App\Game\StandardPlayingCard;
use App\Game\StandardPlayingCardsDeck;
use App\Game\Player;
use App\Game\Round;
use App\Game\Discard;
use App\Game\Game;
use App\Game\GinRummyScoring;

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

    #[Route("/game/init", name: "game_init")]
    public function gameInit(SessionInterface $session): Response
    {
        $deck = new StandardPlayingCardsDeck();
        foreach ($deck->getAllValidCardValues() as $validCard) {
            $deck->add(new StandardPlayingCard(
                $validCard["suit"],
                $validCard["value"]
            ));
        }
        $deck->shuffle();

        $player = new Player(new GinRummyHand());
        $opponent = new Player(new GinRummyHand());

        $round = new Round($player, $opponent);
        $round->randomiseDealer();

        $discard = new Discard();
        $game = new Game(
            $player,
            $opponent,
            $deck,
            $discard
        );

        $game->startRound($round);

        $session->set("game", $game);

        return $this->redirectToRoute('game_start', ['_fragment' => 'ginrummy']);
    }

    #[Route("/game/start", name: "game_start")]
    public function gameStart(SessionInterface $session): Response
    {
        $game = $session->get("game");

        $hand = $game->getPlayerHand();
        $scoring = new GinRummyScoring();
        $hand->resetMelds();
        $scoring->meld($hand);

        return $this->render('pages/game/start.html.twig', [
            'title' => $this->title,
            'game' => $game,
            // 'indexes' => $indexes
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
