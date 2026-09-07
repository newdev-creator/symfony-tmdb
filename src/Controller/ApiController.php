<?php

namespace App\Controller;

use App\Dto\BookRatingDto;
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/books', name: 'api_books_index', methods: ['GET'])]
    public function index(Request $request, BookRepository $bookRepository): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $books = $bookRepository->findBy([], ['title' => 'ASC'], $limit, $offset);
        $total = $bookRepository->count([]);

        return $this->json([
            'data' => $books,
            'pagination' => [
                'current_page' => $page,
                'limit' => $limit,
                'total_items' => $total,
                'total_pages' => ceil($total / $limit),
            ],
        ]);
    }

    #[Route('/book/{id}/rating', name: 'api_book_rating', methods: ['POST'])]
    public function rate(
        int $id,
        Request $request,
        BookRepository $bookRepository,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse {
        $book = $bookRepository->find($id);

        if (!$book) {
            return $this->json(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (!$data || !isset($data['rating'])) {
            return $this->json(['error' => 'Invalid JSON or missing rating'], Response::HTTP_BAD_REQUEST);
        }

        $dto = new BookRatingDto($data['rating']);
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            return $this->json([
                'error' => 'Invalid rating',
                'details' => (string) $errors,
            ], Response::HTTP_BAD_REQUEST);
        }

        $book->setRating($dto->rating);
        $entityManager->flush();

        return $this->json(['message' => 'Rating updated successfully']);
    }
}
