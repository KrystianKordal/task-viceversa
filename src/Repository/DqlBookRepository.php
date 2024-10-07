<?php

namespace App\Repository;

use App\Entity\Book;
use App\Service\Isbn\Isbn13Generator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Faker\Factory;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Book>
 */
class DqlBookRepository extends ServiceEntityRepository
{
    public function __construct(
        private LoggerInterface $logger,
        private Isbn13Generator $isbnGenerator,
        ManagerRegistry         $registry
    )
    {
        parent::__construct($registry, Book::class);
    }

    public function createFakeBooks(int $count): void
    {
        $generator = Factory::create();
        $baseSql = 'INSERT INTO book (title, author, description, publication_year, isbn, cover) VALUES';
        $values = [];
        $connection = $this->getEntityManager()->getConnection();
        for ($i = 0; $i < $count; $i++) {
            $title = $generator->sentence();
            $author = $generator->name();
            $description = $generator->text();
            $publicationYear = $generator->year();
            $isbn = $this->isbnGenerator->generate();
            $values[] = sprintf('("%s", "%s", "%s", %d, %d, null)', $title, $author, $description, $publicationYear, $isbn);

            if ($i % 1000 == 0) {
                $connection->executeStatement(sprintf('%s%s', $baseSql, implode(',', $values)));
                $values = [];
                $this->logger->info(sprintf("Created %d records", $i));
            }
        }

        if (!empty($values)) {
            $connection->executeStatement(sprintf('%s%s', $baseSql, implode(',', $values)));
            $this->logger->info(sprintf("Created %d records", $i));
        }
    }
}
