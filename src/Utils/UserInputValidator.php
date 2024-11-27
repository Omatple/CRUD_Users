<?php

namespace App\Utils;

use App\Database\State;
use App\Database\User;

require __DIR__  . "/../../vendor/autoload.php";
class UserInputValidator
{
    public static function sanitize(string $input)
    {
        return htmlspecialchars(trim($input));
    }

    public static function isValidUsername(string $username): bool
    {
        $minChars = 3;
        $maxChars = 24;
        if (!InputValidator::isValidLength($username, $minChars, $maxChars)) {
            $_SESSION["error_username"] = "Username must be between $minChars and $maxChars characters.";
            return false;
        }
        return true;
    }

    public static function  isValidEmail(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["error_email"] = "Please must be valid email.";
            return false;
        }
        return true;
    }

    public static function isValidStateId(int $id): bool
    {
        if (!in_array($id, State::getStatesId())) {
            $_SESSION["error_stateId"] = "Must choose a valid state.";
            return false;
        }
        return true;
    }

    public static function isNewField(string $field, string $value, ?int $id = null): bool
    {
        if (!User::isFieldUniqueUsers($field, $value, $id)) {
            $_SESSION["error_$field"] = "This $field already exists.";
            return false;
        }
        return true;
    }
}
