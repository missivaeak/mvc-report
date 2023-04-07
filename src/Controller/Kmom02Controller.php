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
    private $title = "mvc.ades22";

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

    #[Route("/card/deck", name: "cardDeck")]
    public function cardDeck(SessionInterface $session): Response
    {
        $deck = $session->get("deck");
        return $this->render('pages/card/deck.html.twig', [
            'title' => $this->title . ".card.deck",
            'deck' => $deck,
        ]);
    }

    #[Route("/card/deck/shuffle", name: "cardShuffle")]
    public function cardShuffle(SessionInterface $session): Response
    {
        $deck = new Deck;
        $deck->shuffle();
        $session->set("deck", $deck);
        return $this->render('pages/card/shuffle.html.twig', [
            'title' => $this->title . ".card.shuffle",
            'deck' => $deck,
        ]);
    }

    #[Route("/card/deck/draw", name: "cardDraw")]
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
    
    #[Route("/card/deck/draw/{num<\d+>}", name: "cardDrawMultiple")]
    public function cardDrawMultiple(SessionInterface $session, int $num): Response
    {
        $deck = $session->get("deck");
        $hand = new Hand;

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
    

    #[Route("/card/deck/deal", name: "cardDeal")]
    public function cardDeal(): Response
    {
        return $this->render('pages/card/deal.html.twig', [
            'title' => $this->title . ".card.deal",
        ]);
    }

    #[Route("/api/card", name: "api_quote")]
    public function apiQuote(): Response
    {
        $quotes = [
            "Sorry losers and haters, but my I.Q. is one of the highest -and you all know it! Please don't feel so stupid or insecure,it's not your fault",
            "Windmills are the greatest threat in the US to both bald and golden eagles. Media claims fictional ‘global warming’ is worse.",
            "Healthy young child goes to doctor, gets pumped with massive shot of many vaccines, doesn't feel good and changes - AUTISM. Many such cases!"
        ];

        $quote = $quotes[array_rand($quotes)];

        $data = [
            'message' => 'Citatmaskinen',
            'quote' => $quote,
            'author' => "Donald J. Trump"
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
