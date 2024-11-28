<?php

namespace App\Utils;

class ErrorDisplay
{
    public static function showError(string $errorKey): void
    {
        if (isset($_SESSION["error_$errorKey"])) {
            echo "<p class='text-xs font-bold text-red-700'>{$_SESSION["error_$errorKey"]}</p>";
            unset($_SESSION["error_$errorKey"]);
        }
    }
}
