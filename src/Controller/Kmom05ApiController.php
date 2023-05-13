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

class Kmom05ApiController extends AbstractController
{
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

    #[Route("/api/library/book/{isbn}", name: "api_library_book", methods: ["GET"])]
    public function apiLibraryBook(
        BookRepository $bookRepo,
        mixed $isbn
    ): Response {
        $book = $bookRepo->findOneBy(['isbn' => $isbn]);
        $data = [];

        if ($book) {
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
