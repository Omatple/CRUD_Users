<?php

namespace App\Utils;

class SessionErrorDisplay
{
    public static function displayError(string $errorName): void
    {
        if (isset($_SESSION["error_$errorName"])) {
            echo "<p class='text-xs font-bold text-red-700'>{$_SESSION["error_$errorName"]}</p>";
            unset($_SESSION["error_$errorName"]);
        }
    }
}
