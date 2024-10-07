<?php

namespace App\Controller;

use App\DTO\BookDTO;
use App\Entity\Book;
use App\Exception\IsbnException;
use App\FormType\BookType;
use App\Service\BookCoverStorageService;
use App\Service\BookCrudService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class BookController extends AbstractController
{
    public function __construct(
        private BookCrudService $bookService,
    )
    {
    }

    #[Route('/', name: 'book_list', methods: ['GET'])]
    public function list(#[MapQueryParameter] int $page = 1): Response
    {
        return $this->render('book/list.html.twig', [
            'books' => $this->bookService->list(page: $page),
            'page' => $page,
            'nextPage' => $this->generateUrl('book_list', ['page' => $page + 1]),
            'prevPage' => $this->generateUrl('book_list', ['page' => $page - 1])
        ]);
    }

    #[Route('/search', name: 'book_search', methods: ['GET'])]
    public function search(
        Request $request,
        #[MapQueryParameter] string $search,
        #[MapQueryParameter] int $page = 1,
    ): Response
    {
        return $this->render('book/list.html.twig', [
            'books' => $this->bookService->search(search: $search, page: $page),
            'page' => $page,
            'nextPage' => $this->generateUrl('book_search', ['search' => $search, 'page' => $page + 1]),
            'prevPage' => $this->generateUrl('book_search', ['search' => $search, 'page' => $page - 1])
        ]);
    }

    #[Route('/show/{id}', name: 'book_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $book = $this->bookService->get($id);

        if (!$book) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/create', name: 'book_create', methods: ['GET', 'POST'])]
    public function formCreate(Request $request): Response
    {
        $form = $this->createForm(BookType::class, new Book());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $book = $this->bookService->save($form);
                return $this->redirectToRoute('book_show', ['id' => $book->getId()]);
            } catch(IsbnException $e) {
                $form->addError(new FormError($e->getMessage()));
            } catch (UniqueConstraintViolationException $e) {
                $form->addError(new FormError('This isbn already exists'));
            }
        }

        return $this->render('book/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'book_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $id,
        Request $request,
    ): Response
    {
        $book = $this->bookService->get($id);
        if (!$book) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $book = $this->bookService->update($form);
                return $this->redirectToRoute('book_show', ['id' => $book->getId()]);

            } catch(IsbnException $e) {
                $form->addError(new FormError($e->getMessage()));
            } catch (UniqueConstraintViolationException $e) {
                $form->addError(new FormError('This isbn already exists'));
            }
        }

        return $this->render('book/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'book_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        $this->bookService->delete($id);
        return $this->redirectToRoute('book_list');
    }
}
