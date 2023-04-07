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
        $deck = new Deck;
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
        $hand = new Hand;

        for ($i = 0; $i < $num; $i++) {
            $hand->add($deck->draw());
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
}
