<?php

namespace App\Tests\Service\Isbn;

use App\Service\Isbn\Isbn13CheckDigitGenerator;
use App\Service\Isbn\Isbn13Validator;
use PHPUnit\Framework\TestCase;

class Isbn13ValidatorTest extends TestCase
{
    public function test_valid_isbn(): void
    {
        $validator = new Isbn13Validator(new Isbn13CheckDigitGenerator());

        $result = $validator->validate(9780000000002);

        $this->assertTrue($result);
    }

    public function test_isbn_with_next_prefix(): void
    {
        $validator = new Isbn13Validator(new Isbn13CheckDigitGenerator());

        $result = $validator->validate(9790000000001);

        $this->assertTrue($result);
    }

    public function test_too_short_isbn(): void
    {
        $validator = new Isbn13Validator(new Isbn13CheckDigitGenerator());

        $result = $validator->validate(978000000002);

        $this->assertFalse($result);
    }

    public function test_isbn_invalid_check_digit(): void
    {
        $validator = new Isbn13Validator(new Isbn13CheckDigitGenerator());

        $result = $validator->validate(9780000000003);

        $this->assertFalse($result);
    }

    public function test_isbn_invalid_prefix(): void
    {
        $validator = new Isbn13Validator(new Isbn13CheckDigitGenerator());

        $result = $validator->validate(9770000000001);

        $this->assertFalse($result);
    }
}
