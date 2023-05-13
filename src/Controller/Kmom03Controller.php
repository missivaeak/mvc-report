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
use App\Game\GinRummyOpponentLogic;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Exception;

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

        $game->setRound($round);
        $game->deal();
        $game->deckToDiscard();
        $playerHand = $game->getPlayerHand();
        $playerHand->resetMelds();
        $scoring = new GinRummyScoring();
        $scoring->meld($playerHand);

        $session->set("game", $game);

        if ($round->getActivePlayer() === $opponent) {
            return $this->redirectToRoute('game_opponent');
        }

        return $this->redirectToRoute('game_main');
    }

    #[Route("/game/main", name: "game_main")]
    public function gameMain(SessionInterface $session): Response
    {
        $game = $session->get("game");
        $scoring = new GinRummyScoring();
        $hand = $game->getPlayerHand();

        $this->addFlash(
            'notice',
            'Gör ditt drag.'
        );

        $hand->resetMelds();
        $scoring->meld($hand);

        return $this->render('pages/game/main.html.twig', [
            'title' => $this->title,
            'game' => $game,
            'scoring' => $scoring
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

    #[Route("/game/opponent", name: "game_opponent")]
    public function gameOpponent(SessionInterface $session): Response
    {
        $game = $session->get("game");
        $round = $game->getRound();
        $scoring = new GinRummyScoring();
        $logic = new GinRummyOpponentLogic($game, $scoring);
        $player = $game->getPlayer();
        $opponent = $game->getOpponent();
        $playerHand = $game->getPlayerHand();

        if ($game->getDeck()->getCardsRemaining() < 3) {
            $this->addFlash('notice', 'Kortleken tar slut och rundan avslutas. Inga poäng delas ut.');
            return $this->redirectToRoute('game_end_round');
        }

        $this->addFlash(
            'notice',
            'Motståndaren påbörjar sitt drag.'
        );

        $step = $round->getStep();
        $flash = "";

        switch ($step) {
            case 0:
            case 1:
            case 2:
                $flash = $logic->mainStep($round);
                break;
            case 3:
                $flash = $logic->knockStep($round, $playerHand);
                $flash .= $scoring->checkScoreDiff($player, $opponent, $game);
                break;
            case 4:
            case 5:
                $flash = $logic->topCardChoiceStep($round);
                break;
            case 6:
                $flash = $logic->topCardForcedStep($round);
                break;
        }

        $this->addFlash('notice', $flash);

        if ($game->getDeck()->getCardsRemaining() < 3) {
            $this->addFlash('notice', 'Kortleken tar slut och rundan avslutas. Inga poäng delas ut.');
            return $this->redirectToRoute('game_end_round');
        }

        $round->nextTurn();

        return $this->redirectToRoute('game_main');
    }

    #[Route("/game/draw/deck", name: "game_draw_deck")]
    public function gameDrawDeck(SessionInterface $session): Response
    {
        $game = $session->get("game");
        $round = $game->getRound();
        $deck = $game->getDeck();
        $hand = $game->getPlayerHand();

        $card = $deck->draw();
        $hand->add($card);

        $round->setStep(1);

        return $this->redirectToRoute('game_main');
    }

    #[Route("/game/draw/discard", name: "game_draw_discard")]
    public function gameDrawDiscard(SessionInterface $session): Response
    {
        $game = $session->get("game");
        $round = $game->getRound();
        $discard = $game->getDiscard();
        $hand = $game->getPlayerHand();

        $card = $discard->draw();
        $hand->add($card);

        $round->setStep(1);

        return $this->redirectToRoute('game_main');
    }

    #[Route("/game/discard/{suit}/{value<\d+>}", name: "game_discard")]
    public function gameDiscard(
        SessionInterface $session,
        string $suit,
        int $value
    ): Response {
        $game = $session->get("game");
        $round = $game->getRound();
        $hand = $game->getPlayerHand();
        $discard = $game->getDiscard();

        $card = $hand->drawByPattern($suit, $value);
        $discard->add($card);
        $round->setStep(2);
        $round->nextTurn();

        return $this->redirectToRoute('game_main');
    }

    #[Route("/game/meld/{suit}/{value<\d+>}", name: "game_meld")]
    public function gameMeld(
        SessionInterface $session,
        string $suit,
        int $value
    ): Response {
        $game = $session->get("game");
        $scoring = new GinRummyScoring();
        $playerHand = $game->getPlayerHand();
        $opponentHand = $game->getOpponentHand();

        $scoring->addToOthersMeld($suit, $value, $playerHand, $opponentHand);
        $opponentHand->revealAll();

        return $this->redirectToRoute('game_main');
    }

    #[Route("/game/pass", name: "game_pass")]
    public function gamePass(SessionInterface $session): Response
    {
        $game = $session->get("game");
        $round = $game->getRound();
        $round->setStep(0);
        $round->nextTurn();

        return $this->redirectToRoute('game_opponent');
    }

    #[Route("/game/meld/pass", name: "game_meld_pass")]
    public function gameMeldPass(SessionInterface $session): Response
    {
        $game = $session->get("game");
        $scoring = new GinRummyScoring();
        $player = $game->getPlayer();
        $opponent = $game->getOpponent();
        $playerScore = $scoring->handScore($player->getHand());
        $opponentScore = $scoring->handScore($opponent->getHand());

        $difference = $playerScore - $opponentScore;
        $points = $game->score($opponent, $player, $difference);
        $flash = "Lika. Inga poäng delades ut.";

        if ($opponentScore === 0) {
            $points = $playerScore + $game->getGinBonus();
            $flash = "Motståndaren har gin och får $points poäng.";
        } else {
            if ($points > 0) {
                $flash = "Motståndaren vinner och får $points poäng.";
            } elseif ($points < 0) {
                $points = abs($points);
                $flash = "Du vinner och får $points poäng.";
            }
        }
        $this->addFlash('notice', $flash);

        return $this->redirectToRoute('game_end_round');
    }

    #[Route("/game/knock", name: "game_knock")]
    public function gameKnock(SessionInterface $session): Response
    {
        $game = $session->get("game");
        $opponentHand = $game->getOpponentHand();
        $scoring = new GinRummyScoring();

        $scoring->meld($opponentHand);
        $opponentHand->revealAll();

        $round = $game->getRound();
        $round->setStep(3);
        $round->nextTurn();

        return $this->redirectToRoute('game_opponent');
    }

    #[Route("/game/end/round", name: "game_end_round")]
    public function gameEndRound(SessionInterface $session): Response
    {
        $game = $session->get("game");
        $round = $game->getRound();
        $player = $game->getPlayer();
        $opponent = $game->getOpponent();

        if ($player->getScore() >= 100 || $opponent->getScore() >= 100) {
            $flashBag = $session->getBag('flashes');
            $flashBag->clear();
            return $this->redirectToRoute('game_end_game');
        }

        // fixa inför nästa runda
        $nextDealer = $round->getNextDealer();
        $round = new Round($player, $opponent);
        $round->setDealer($nextDealer);
        $round->autoSetActivePlayer();
        $game->returnCards();
        $game->getDeck()->shuffle();
        $game->getDeck()->hideAll();
        $game->setRound($round);
        $game->deal();
        $game->deckToDiscard();

        return $this->redirectToRoute('game_opponent');
    }

    #[Route("/game/end/game", name: "game_end_game")]
    public function gameEndGame(
        SessionInterface $session
    ): Response {
        $game = $session->get("game");
        $session->remove("game");

        return $this->render('pages/game/end.html.twig', [
            'title' => $this->title,
            'game' => $game
        ]);
    }
}
