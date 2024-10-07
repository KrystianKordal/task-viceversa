<?php

namespace App\Service\Isbn;

class Isbn13Generator
{
    private const ISBN_PREFIX = 978;

    private int $currentNumber = 0;

    public function __construct(
        private Isbn13CheckDigitGenerator $checkDigitGenerator,
    )
    {
    }


    public function generate(): string
    {
        $isbn = self::ISBN_PREFIX . sprintf('%09d', $this->currentNumber);
        $isbn .= $this->checkDigitGenerator->generateCheckDigit($isbn);

        $this->currentNumber++;
        return $isbn;
    }


}