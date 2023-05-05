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

class ApiController extends AbstractController
{
    private string $title = "mvc.ades22";

    #[Route("/api", name: "api")]
    public function api(): Response
    {
        return $this->render('pages/api.html.twig', [
            'title' => $this->title . ".api",
        ]);
    }

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

    #[Route("/api/deck/shuffle", name: "api_shuffle", methods: ["GET", "POST"])]
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

    #[Route("/api/deck/draw", name: "api_draw", methods: ["GET", "POST"])]
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

    #[Route("/api/deck/draw/{num<\d+>}", name: "api_draw_multiple", methods: ["GET", "POST"])]
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

    #[Route("/api/deck/deal/{players<\d+>}/{cards<\d+>}", name: "api_deal", methods: ["GET", "POST"])]
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

    #[Route("/api/library/books", name: "api_library_books", methods: ["GET"])]
    public function apiLibraryBooks(
        BookRepository $bookRepo,
    ): Response {
        $data = [];
        $books = $bookRepo->findAll();
        foreach ($books as $book) {
            $data[] = [
                'title' => $book->getTitle() ?? null,
                'author' => $book->getAuthor() ?? null,
                'isbn' => $book->getIsbn() ?? null,
                'imageUrl' => $book->getImageUrl() ?? null,
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
