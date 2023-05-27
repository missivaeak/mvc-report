<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class Kmom05Controller extends AbstractController
{
    private string $title = "mvc.ades22.library";

    #[Route('/library', name: 'library')]
    public function index(
        BookRepository $bookRepo
    ): Response {
        $books = $bookRepo->findAll();

        return $this->render('pages/library/index.html.twig', [
            'title' => $this->title,
            'books' => $books
        ]);
    }

    #[Route('/library/create', name: 'library_create', methods: ["GET", "POST"])]
    public function libraryCreate(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        if ($request->isMethod("GET")) {
            return $this->render('pages/library/create.html.twig', [
                'title' => $this->title,
            ]);
        }

        $title = strval($request->request->get('title'));
        $isbn = strval($request->request->get('isbn'));
        $author = strval($request->request->get('author'));
        $imageUrl = strval($request->request->get('image_url'));

        $book = new Book();
        $book->setTitle($title);
        $book->setIsbn($isbn);
        $book->setAuthor($author);
        $book->setImageUrl($imageUrl);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($book);
        $entityManager->flush();

        $this->addFlash('notice', 'Boken har lagts till.');

        return $this->redirectToRoute('library');
    }

    #[Route('/library/read/{id<\d+>}', name: 'library_read_one')]
    public function libraryReadOne(
        BookRepository $bookRepo,
        int $id
    ): Response {
        $book = $bookRepo->find($id);

        if (!$book) {
            $this->addFlash('notice', "Boken hittades inte.");

            return $this->redirectToRoute('library');
        }

        return $this->render('pages/library/book.html.twig', [
            'title' => $this->title,
            'book' => $book
        ]);
    }

    #[Route('/library/update/{id<\d+>}', name: 'library_update_book', methods: ["GET", "POST"])]
    public function libraryUpdateBook(
        BookRepository $bookRepo,
        Request $request,
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $book = $bookRepo->find($id);

        if (!$book) {
            $this->addFlash('notice', "Boken hittades inte.");

            return $this->redirectToRoute('library_update');
        }

        if ($request->isMethod("GET")) {
            return $this->render('pages/library/edit.html.twig', [
                'title' => $this->title,
                'book' => $book
            ]);
        }

        $title = strval($request->request->get('title'));
        $isbn = strval($request->request->get('isbn'));
        $author = strval($request->request->get('author'));
        $imageUrl = strval($request->request->get('image_url'));

        $book->setTitle($title);
        $book->setIsbn($isbn);
        $book->setAuthor($author);
        $book->setImageUrl($imageUrl);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($book);
        $entityManager->flush();

        $this->addFlash('notice', "Ändringar har sparats.");

        return $this->redirectToRoute('library');
    }

    #[Route('/library/delete', name: 'library_delete', methods: ["GET", "POST"])]
    public function libraryDelete(
        Request $request,
        BookRepository $bookRepo,
        ManagerRegistry $doctrine
    ): Response {
        $bookId = $request->request->get('book-id');
        $book = $bookRepo->find($bookId);
        $flash = "Hittade inte den produkten i databasen.";

        if ($book) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
            $flash = "Tog bort " . $book->getTitle() . ".";
        }

        $this->addFlash('notice', $flash);

        return $this->redirectToRoute('library');
    }

    #[Route('/library/reset', name: 'library_reset', methods: ["GET", "POST"])]
    public function libraryReset(
        ManagerRegistry $doctrine
    ): Response {
        /** @var Connection $connection */
        $connection = $doctrine->getConnection();

        $file = '../assets/data_backup.sql';
        $sql = '
        ' . file_get_contents($file);

        $connection->executeStatement($sql);
        $doctrine->getManager()->flush();

        $this->addFlash('notice', "Databasen återställd.");

        return $this->redirectToRoute('library');
    }
}
