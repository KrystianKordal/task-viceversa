<?php

namespace App\Service\Isbn;

class Isbn13Validator
{
    private const ISBN_LENGTH = 13;
    private const ISBN_PREFIXES = [978, 979];

    public function __construct(
        private readonly Isbn13CheckDigitGenerator $checkDigitGenerator,
    )
    {
    }

    public function validate(string $isbn): bool
    {
        if (strlen($isbn) != self::ISBN_LENGTH) {
            return false;
        }

        if (!in_array(substr($isbn, 0, 3), self::ISBN_PREFIXES)) {
            return false;
        }

        $first12Digits = substr($isbn, 0, 12);
        $validCheckDigit = $this->checkDigitGenerator->generateCheckDigit($first12Digits);
        $currentCheckDigit = substr($isbn, 12, 1);
        if ($validCheckDigit != $currentCheckDigit) {
            return false;
        }

        return true;
    }
}