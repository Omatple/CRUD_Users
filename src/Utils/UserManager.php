<?php

namespace App\Utils;

use App\Database\User;

require __DIR__ . "/../../vendor/autoload.php";
class UserManager
{
    public static function deleteUserRecursive(int $id, string $image): void
    {
        User::deleteUser($id);
        ImageProcessor::deleteLastImage($image);
    }
}
