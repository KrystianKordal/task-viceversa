<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class BookCoverStorageService
{
    public function __construct(
        private SluggerInterface $slugger,

        #[Autowire('%kernel.project_dir%/public/uploads/covers')]
        private string $coversDirectory,
        private LoggerInterface $logger,
    )
    {
    }

    public function storeCover(?UploadedFile $cover): ?string
    {
        if (!$cover) {
            return null;
        }

        $originalFilename = pathinfo($cover->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$cover->guessExtension();

        try {
            $cover->move($this->coversDirectory, $newFilename);
        } catch (FileException $e) {
            $this->logger->error($e);
        }

        return $newFilename;
    }

    public function removeCover(?string $cover): void
    {
        $path = $this->coversDirectory . $cover;
        if ($cover && file_exists($path)) {
            unlink($path);
        }
    }
}