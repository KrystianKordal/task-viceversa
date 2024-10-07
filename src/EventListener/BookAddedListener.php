<?php

namespace App\EventListener;

use App\Event\BookAddedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener]
class BookAddedListener
{
    public function __construct(
        private LoggerInterface $addedBookLogger,
        private SerializerInterface $serializer
    )
    {
    }

    public function __invoke(BookAddedEvent $event): void
    {
        $this->addedBookLogger->info($this->serializer->serialize($event->getBook(), 'json'));
    }
}