<?php

namespace App\Utils;

class Navigator
{
    public static function redirect(string $url): void
    {
        header("Location: $url");
        exit();
    }

    public static function refresh(): void
    {
        header("Location: {$_SERVER["PHP_SELF"]}");
        exit();
    }
}
