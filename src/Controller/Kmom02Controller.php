<?php

namespace App\Controller;

use App\Card\Deck;
use App\Card\Hand;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use TypeError;

class Kmom02Controller extends AbstractController
{
    private string $title = "mvc.ades22";

    #[Route("/card", name: "card")]
    public function card(SessionInterface $session): Response
    {
        if (!$session->get("deck")) {
            $session->set("deck", new Deck());
        }

        return $this->render('pages/card/index.html.twig', [
            'title' => $this->title . ".card",
        ]);
    }

    #[Route("/card/deck", name: "card_deck")]
    public function cardDeck(SessionInterface $session): Response
    {
        $deck = $session->get("deck");
        return $this->render('pages/card/deck.html.twig', [
            'title' => $this->title . ".card.deck",
            'deck' => $deck,
        ]);
    }

    #[Route("/card/deck/shuffle", name: "card_shuffle")]
    public function cardShuffle(SessionInterface $session): Response
    {
        $deck = new Deck();
        $deck->shuffle();
        $session->set("deck", $deck);
        return $this->render('pages/card/shuffle.html.twig', [
            'title' => $this->title . ".card.shuffle",
            'deck' => $deck,
        ]);
    }

    #[Route("/card/deck/draw", name: "card_draw")]
    public function cardDraw(SessionInterface $session): Response
    {
        $deck = $session->get("deck");
        try {
            $card = $deck->draw();
        } catch (TypeError $e) {
            $card = null;
        }
        return $this->render('pages/card/draw.html.twig', [
            'title' => $this->title . ".card.draw",
            'deck' => $deck,
            'card' => $card,
        ]);
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "card_draw_multiple")]
    public function cardDrawMultiple(
        SessionInterface $session,
        int $num
    ): Response {
        $deck = $session->get("deck");
        $hand = new Hand();

        for ($i = 0; $i < $num; $i++) {
            try {
                $hand->add($deck->draw());
            } catch (TypeError $e) {
                break;
            }
        }

        return $this->render('pages/card/drawMultiple.html.twig', [
            'title' => $this->title . ".card.drawMultiple",
            'deck' => $deck,
            'hand' => $hand,
        ]);
    }


    #[Route("/card/deck/deal/{players<\d+>}/{cards<\d+>}", name: "card_deal")]
    public function cardDeal(
        SessionInterface $session,
        int $players,
        int $cards
    ): Response {
        $deck = $session->get("deck");
        $hands = [];

        // ge varje spela en hand
        for ($i = 0; $i < $players; $i++) {
            $hands[] = new Hand();
        }

        // dela ut ett kort per spelare ända tills det är färdigt eller leken är slut
        for ($j = 0; $j < $cards; $j++) {
            foreach ($hands as $hand) {
                try {
                    $hand->add($deck->draw());
                } catch (TypeError $e) {
                    break;
                }
            }
        }

        return $this->render('pages/card/deal.html.twig', [
            'title' => $this->title . ".card.deal",
            'deck' => $deck,
            'hands' => $hands,
        ]);
    }
}
