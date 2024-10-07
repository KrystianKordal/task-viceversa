<?php

namespace App\Service;

use App\Entity\Book;
use App\Event\BookAddedEvent;
use App\Exception\IsbnException;
use App\Repository\BookRepository;
use App\Service\Isbn\Isbn13Validator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;

readonly class BookCrudService
{
    private const PAGE_SIZE = 15;
    public function __construct(
        private BookRepository $repository,
        private Isbn13Validator $isbnValidator,
        private EventDispatcherInterface $dispatcher,
        private BookCoverStorageService $coverStorage,
    )
    {
    }

    public function list(int $page = 1): array
    {
        return $this->repository->findBy([], limit: self::PAGE_SIZE, offset: self::PAGE_SIZE * ($page - 1));
    }

    public function search(?string $search, int $page = 1): array
    {
        return $this->repository->search(search: $search, page: $page);
    }

    public function get(int $id): ?Book
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function save(FormInterface $form): Book
    {
        $book = $form->getData();
        if (!$this->isbnValidator->validate($book->getIsbn())) {
            throw new IsbnException("Invalid ISBN");
        }

        $newCover = $this->coverStorage->storeCover($form->get('cover')->getData());
        if ($newCover) {
            $book->setCover($newCover);
        }

        $this->repository->save($book);

        $this->dispatcher->dispatch(new BookAddedEvent($book));

        return $book;
    }

    public function update(FormInterface $form): Book
    {
        $book = $form->getData();

        if (!$this->isbnValidator->validate($book->getIsbn())) {
            throw new IsbnException("Invalid ISBN");
        }

        $newCover = $this->coverStorage->storeCover($form->get('cover')->getData());
        if ($newCover) {
            $this->coverStorage->removeCover($book->getCover());
            $book->setCover($newCover);
        }

        $this->repository->update($book);

        return $book;
    }

    public function delete(int $id): void
    {
        $book = $this->repository->findOneBy(['id' => $id]);
        $this->repository->delete($book);
    }
}