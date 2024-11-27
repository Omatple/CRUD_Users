<?php

use App\Database\User;
use App\Utils\Navigation;
use App\Utils\UserManager;

session_start();
require __DIR__ . "/../vendor/autoload.php";
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if ($id && ($image = User::getImageById($id))) {
    UserManager::deleteUserRecursive($id, $image);
    $_SESSION["message"] = "User delete successly";
}
Navigation::redirectTo("users.php");
