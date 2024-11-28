<?php

use App\Database\UserEntity;
use App\Utils\Navigator;
use App\Utils\UserManager;

session_start();
require __DIR__ . "/../vendor/autoload.php";

$userId = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if ($userId && ($imagePath = UserEntity::fetchImageById($userId))) {
    UserManager::removeUserAndImage($userId, $imagePath);
    $_SESSION["message"] = "User deleted successfully.";
}

Navigator::redirect("users.php");
