<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Kmom01Controller extends AbstractController
{
    private $title = "mvc.ades22";

    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('pages/home.html.twig', [
            'title' => $this->title,
        ]);
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('pages/about.html.twig', [
            'title' => $this->title . ".about",
        ]);
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('pages/report.html.twig', [
            'title' => $this->title . ".report",
        ]);
    }

    #[Route("/lucky", name: "lucky")]
    public function lucky(): Response
    {
        $number = random_int(3, 4);

        return $this->render('pages/lucky.html.twig', [
            'title' => $this->title . ".lucky",
            'number' => $number,
        ]);
    }

    #[Route("/api/quote", name: "api_quote")]
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
