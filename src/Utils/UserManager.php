<?php

namespace App\Utils;

use App\Database\UserEntity;

require __DIR__ . "/../../vendor/autoload.php";

class UserManager
{
    public static function removeUserAndImage(int $userId, string $imagePath): void
    {
        UserEntity::removeUser($userId);
        ImageHandler::deleteImage($imagePath);
    }
}
