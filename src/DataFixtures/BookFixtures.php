<?php

namespace App\DataFixtures;

use App\Repository\DqlBookRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    private const BOOKS_COUNT = 1000001;

    public function __construct(
        private DqlBookRepository $repository
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->repository->createFakeBooks(self::BOOKS_COUNT);
    }
}
