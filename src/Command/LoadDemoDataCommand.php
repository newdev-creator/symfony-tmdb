<?php

namespace App\Command;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:load-demo-data',
    description: 'Charges des données de démo pour les livres',
)]
class LoadDemoDataCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $books = [
            ['title' => 'Le Petit Prince', 'author' => 'Antoine de Saint-Exupéry', 'isbn' => '9782070408504', 'rating' => 5, 'status' => 'lu'],
            ['title' => '1984', 'author' => 'George Orwell', 'isbn' => '9780451524935', 'rating' => 4, 'status' => 'lu'],
            ['title' => 'Le Seigneur des Anneaux', 'author' => 'J.R.R. Tolkien', 'isbn' => '9780261102354', 'rating' => 5, 'status' => 'en cours'],
            ['title' => 'L\'Étranger', 'author' => 'Albert Camus', 'isbn' => '9782070360024', 'rating' => 3, 'status' => 'lu'],
            ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'isbn' => '9780141439518', 'rating' => 4, 'status' => 'à lire'],
            ['title' => 'Crime and Punishment', 'author' => 'Fyodor Dostoevsky', 'isbn' => '9780140449136', 'rating' => 5, 'status' => 'en cours'],
            ['title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'isbn' => '9780743273565', 'rating' => 3, 'status' => 'lu'],
            ['title' => 'Moby Dick', 'author' => 'Herman Melville', 'isbn' => '9780142437247', 'rating' => null, 'status' => 'à lire'],
            ['title' => 'Ulysses', 'author' => 'James Joyce', 'isbn' => '9780141182806', 'rating' => 2, 'status' => 'en cours'],
            ['title' => 'War and Peace', 'author' => 'Leo Tolstoy', 'isbn' => '9780307266934', 'rating' => 5, 'status' => 'à lire'],
            ['title' => 'The Hobbit', 'author' => 'J.R.R. Tolkien', 'isbn' => '9780547928227', 'rating' => 5, 'status' => 'lu'],
            ['title' => 'Brave New World', 'author' => 'Aldous Huxley', 'isbn' => '9780060850524', 'rating' => 4, 'status' => 'lu'],
            ['title' => 'The Catcher in the Rye', 'author' => 'J.D. Salinger', 'isbn' => '9780316769488', 'rating' => 3, 'status' => 'lu'],
            ['title' => 'One Hundred Years of Solitude', 'author' => 'Gabriel García Márquez', 'isbn' => '9780060883287', 'rating' => 5, 'status' => 'en cours'],
            ['title' => 'The Alchemist', 'author' => 'Paulo Coelho', 'isbn' => '9780062315007', 'rating' => 4, 'status' => 'lu'],
            ['title' => 'Fahrenheit 451', 'author' => 'Ray Bradbury', 'isbn' => '9781451673319', 'rating' => 4, 'status' => 'à lire'],
            ['title' => 'The Odyssey', 'author' => 'Homer', 'isbn' => '9780140268867', 'rating' => null, 'status' => 'à lire'],
            ['title' => 'Don Quixote', 'author' => 'Miguel de Cervantes', 'isbn' => '9780060934067', 'rating' => 5, 'status' => 'lu'],
            ['title' => 'Madame Bovary', 'author' => 'Gustave Flaubert', 'isbn' => '9782070360017', 'rating' => 3, 'status' => 'lu'],
            ['title' => 'The Divine Comedy', 'author' => 'Dante Alighieri', 'isbn' => '9780141744722', 'rating' => 4, 'status' => 'en cours'],
        ];

        foreach ($books as $data) {
            $book = new Book();
            $book->setTitle($data['title']);
            $book->setAuthor($data['author']);
            $book->setIsbn($data['isbn']);
            $book->setRating($data['rating']);
            $book->setStatus($data['status']);

            $this->entityManager->persist($book);
        }

        $this->entityManager->flush();

        $io->success('20 livres de démo ont été chargés avec succès.');

        return Command::SUCCESS;
    }
}
