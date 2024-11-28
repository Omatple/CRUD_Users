<?php

namespace App\Utils;

class InputValidator
{
    public static function isLengthValid(string $input, int $minLength, int $maxLength): bool
    {
        return strlen($input) >= $minLength && strlen($input) <= $maxLength;
    }
}
