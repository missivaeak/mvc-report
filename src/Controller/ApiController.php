<?php

namespace App\Controller;

use App\Card\Deck;
use App\Card\Hand;
use App\Card\Card;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use TypeError;

class ApiController extends AbstractController
{
    #[Route("/api/deck", name: "api_deck", methods: ["GET"])]
    public function apiDeck(SessionInterface $session): Response
    {
        $deck = $session->get("deck");
        $cards = $deck->peekAllCards();
        sort($cards);

        $data = [
            'cards' => $cards
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle", methods: ["POST"])]
    public function apiShuffle(SessionInterface $session): Response
    {
        $deck = new Deck();
        $deck->shuffle();
        $session->set("deck", $deck);

        $data = [
            'cards' => $deck->peekAllCards()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/draw", name: "api_draw", methods: ["POST"])]
    public function apiDraw(SessionInterface $session): Response
    {
        $deck = $session->get("deck");
        $card = $deck->draw();

        $data = [
            'cardsRemaining' => $deck->getCardsRemaining(),
            'card' => $card->peek()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/draw/{num<\d+>}", name: "api_draw_multiple", methods: ["POST"])]
    public function apiDrawMultiple(SessionInterface $session, int $num): Response
    {
        $deck = $session->get("deck");
        $hand = new Hand();

        for ($i = 0; $i < $num; $i++) {
            try {
                $hand->add($deck->draw());
            } catch (TypeError $e) {
                break;
            }
        }

        $data = [
            'cardsRemaining' => $deck->getCardsRemaining(),
            'cards' => $hand->peekAllCards()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/deal/{players<\d+>}/{cards<\d+>}", name: "api_deal", methods: ["POST"])]
    public function apiDeal(
        SessionInterface $session,
        int $players,
        int $cards
    ): Response {
        $deck = $session->get("deck");
        $hands = [];

        // ge varje spela en hand
        for ($i = 1; $i <= $players; $i++) {
            $hands["player" . $i] = new Hand();
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

        $data = [
            'cardsRemaining' => $deck->getCardsRemaining(),
            'hands' => array_map(function ($hand) { return $hand->peekAllCards(); }, $hands)
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
