<?php

namespace App\Command;

use App\Entity\Book;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-data',
    description: 'Seeds the database with demo books and reviews',
)]
class SeedDataCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Seeding data...');

        $titles = ['The Great Gatsby', '1984', 'To Kill a Mockingbird', 'The Catcher in the Rye', 'The Hobbit', 'Brave New World', 'Moby Dick', 'War and Peace', 'Crime and Punishment', 'Ulysses'];
        $authors = ['F. Scott Fitzgerald', 'George Orwell', 'Harper Lee', 'J.D. Salinger', 'J.R.R. Tolkien', 'Aldous Huxley', 'Herman Melville', 'Leo Tolstoy', 'Fyodor Dostoevsky', 'James Joyce'];
        $reviewTexts = ['Amazing book!', 'I loved it.', 'A bit slow but good.', 'Must read.', 'Not for me.', 'Classic!', 'Changed my life.', 'Deeply moving.', 'Well written.', 'Interesting perspective.'];
        $reviewAuthors = ['Alice', 'Bob', 'Charlie', 'Diana', 'Eve', 'Frank', 'Grace', 'Heidi', 'Ivan', 'Judy'];

        for ($i = 1; $i <= 500; $i++) {
            $book = new Book();
            $book->setTitle($titles[array_rand($titles)] . ' ' . $i);
            $book->setAuthor($authors[array_rand($authors)]);
            $book->setIsbn('ISBN-' . str_pad((string)$i, 13, '0', STR_PAD_LEFT));
            $book->setRating(rand(1, 5));
            $book->setStatus(['à lire', 'en cours', 'lu'][array_rand(['à lire', 'en cours', 'lu'])]);

            $this->entityManager->persist($book);

            $numReviews = rand(0, 20);
            for ($j = 0; $j < $numReviews; $j++) {
                $review = new Review();
                $review->setAuthor($reviewAuthors[array_rand($reviewAuthors)]);
                $review->setContent($reviewTexts[array_rand($reviewTexts)]);
                $review->setBook($book);
                $this->entityManager->persist($review);
            }

            if ($i % 50 === 0) {
                $this->entityManager->flush();
                $io->text("Processed $i books...");
            }
        }

        $this->entityManager->flush();
        $io->success('Database seeded successfully!');

        return Command::SUCCESS;
    }
}
