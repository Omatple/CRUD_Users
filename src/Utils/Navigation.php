<?php

namespace App\Utils;

class Navigation
{
    public static function redirectTo(string $urlPage): void
    {
        header("Location: $urlPage");
        exit();
    }

    public static function reloadPage(): void
    {
        header("Location: {$_SERVER["PHP_SELF"]}");
        exit();
    }
}
