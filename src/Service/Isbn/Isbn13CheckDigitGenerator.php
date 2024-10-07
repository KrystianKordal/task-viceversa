<?php

namespace App\Service\Isbn;

class Isbn13CheckDigitGenerator
{
    private const EVEN_DIGIT_MULTIPLIER = 3;
    private const ODD_DIGIT_MULTIPLIER = 1;

    public function generateCheckDigit(string $isbn): int
    {
        $sum = 0;
        foreach (str_split($isbn) as $i => $digit) {
            $multiplier = ($i % 2 == 0) ? self::ODD_DIGIT_MULTIPLIER : self::EVEN_DIGIT_MULTIPLIER;
            $sum += $digit * $multiplier;
        }

        return (10 - ($sum % 10)) % 10;
    }
}