<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function search(string $search, int $page): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.title LIKE :search')
            ->orWhere('b.author LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->setFirstResult(($page - 1) * 15)
            ->setMaxResults(15)
            ->getQuery()->getArrayResult();
    }

    public function save(Book $book): void
    {
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
    }

    public function update(Book $book): void
    {
        $this->getEntityManager()->flush();
    }

    public function delete(Book $book): void
    {
        $this->getEntityManager()->remove($book);
        $this->getEntityManager()->flush();
    }
}
