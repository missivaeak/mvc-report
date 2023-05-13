<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Kmom01Controller extends AbstractController
{
    private string $title = "mvc.ades22";

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
}
