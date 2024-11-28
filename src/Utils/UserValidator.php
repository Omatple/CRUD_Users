<?php

namespace App\Utils;

use App\Database\StateEntity;
use App\Database\UserEntity;

require __DIR__  . "/../../vendor/autoload.php";

class UserValidator
{
    public static function sanitizeInput(string $input): string
    {
        return htmlspecialchars(trim($input));
    }

    public static function validateUsername(string $username): bool
    {
        $minLength = 3;
        $maxLength = 24;
        if (!InputValidator::isLengthValid($username, $minLength, $maxLength)) {
            $_SESSION["error_username"] = "Username must be between $minLength and $maxLength characters.";
            return false;
        }
        return true;
    }

    public static function validateEmail(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["error_email"] = "Please provide a valid email.";
            return false;
        }
        return true;
    }

    public static function validateStateId(int $stateId): bool
    {
        if (!in_array($stateId, StateEntity::fetchStateIds())) {
            $_SESSION["error_stateId"] = "Please choose a valid state.";
            return false;
        }
        return true;
    }

    public static function isUniqueField(string $field, string $value, ?int $excludeId = null): bool
    {
        if (!UserEntity::isUserFieldUnique($field, $value, $excludeId)) {
            $_SESSION["error_$field"] = "This $field already exists.";
            return false;
        }
        return true;
    }
}
