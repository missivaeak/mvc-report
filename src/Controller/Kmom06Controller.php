<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class Kmom06Controller extends AbstractController
{
    private string $title = "mvc.ades22.metrics";

    #[Route('/metrics', name: 'metrics')]
    public function index(): Response
    {
        return $this->render('pages/metrics.html.twig', [
            'title' => $this->title
        ]);
    }
}
