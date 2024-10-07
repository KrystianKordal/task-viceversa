<?php

namespace App\Event;

use App\Entity\Book;
use Symfony\Contracts\EventDispatcher\Event;

class BookAddedEvent extends Event
{
    public function __construct(
        private readonly Book $book
    )
    {
    }

    public function getBook(): Book
    {
        return $this->book;
    }
}