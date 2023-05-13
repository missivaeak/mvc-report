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

class Kmom01ApiController extends AbstractController
{
    #[Route("/api", name: "api")]
    public function api(): Response
    {
        return $this->render('pages/api.html.twig', [
            'title' => "mvc.ades22.api",
        ]);
    }

    #[Route("/api/quote", name: "api_quote", methods: ["GET"])]
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
