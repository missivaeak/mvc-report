<?php

namespace App\Controller;

use App\Card\Deck;
use App\Card\Hand;

use App\Repository\BookRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use TypeError;

class Kmom03ApiController extends AbstractController
{
    #[Route("/api/game", name: "api_game", methods: ["GET"])]
    public function apiGame(SessionInterface $session): Response
    {
        $game = $session->get("game");
        if ($game) {
            $playerScore = $game->getPlayer()->getScore();
            $opponentScore = $game->getOpponent()->getScore();
            $deck = $game->getDeck()->getFaces();
            $discard = $game->getDiscard()->getFaces();
            $playerHand = $game->getPlayer()->getHand()->getFaces();
            $opponentHand = $game->getOpponent()->getHand()->getFaces();
        }

        $data = [
            'playerScore' => $playerScore ?? null,
            'opponentScore' => $opponentScore ?? null,
            'playerHand' => $playerHand ?? null,
            'opponentHand' => $opponentHand ?? null,
            'deck' => $deck ?? null,
            'discard' => $discard ?? null,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
