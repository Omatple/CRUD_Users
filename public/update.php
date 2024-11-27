<?php

use App\Database\State;
use App\Database\User;
use App\Utils\ImageConstants;
use App\Utils\ImageProcessor;
use App\Utils\Navigation;
use App\Utils\SessionErrorDisplay;
use App\Utils\UserInputValidator;

session_start();

require __DIR__ . "/../vendor/autoload.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id || !$user = User::getUserById($id)) Navigation::redirectTo("users.php");
if (isset($_POST["resetDefaultImage"])) {
    if (basename($user["image"]) !== ImageConstants::IMAGE_DEFAULT_FILENAME) {
        User::resetImageDefault($id);
        ImageProcessor::deleteLastImage($user["image"]);
        $user["image"] = "img/" . ImageConstants::IMAGE_DEFAULT_FILENAME;
    }
} elseif (isset($_POST["username"])) {
    $username = UserInputValidator::sanitize($_POST["username"]);
    $email = UserInputValidator::sanitize($_POST["email"]);
    $stateId = (int) UserInputValidator::sanitize($_POST["stateId"]);
    $hasErrors = false;
    if (!UserInputValidator::isValidUsername($username)) $hasErrors = true;
    if (!$hasErrors && !UserInputValidator::isNewField("username", $username, $id)) $hasErrors = true;
    if (!UserInputValidator::isValidEmail($email)) {
        $hasErrors = true;
    } elseif (!UserInputValidator::isNewField("email", $email, $id)) {
        $hasErrors = true;
    }
    if (!UserInputValidator::isValidStateId($stateId)) $hasErrors = true;
    if (ImageProcessor::isValidError($_FILES["image"]["error"]) && !ImageProcessor::isValidImage($_FILES["image"])) $hasErrors = true;
    if ($hasErrors) Navigation::redirectTo($_SERVER['PHP_SELF'] . "?id=$id");
    $image = $user["image"];
    if (ImageProcessor::isValidError($_FILES["image"]["error"]) && !ImageProcessor::moveImage($_FILES["image"]["tmp_name"], ($image = ImageProcessor::generateUrlUniqueName($_FILES["image"]["name"])))) Navigation::redirectTo($_SERVER['PHP_SELF'] . "?id=$id");
    (new User)
        ->setUsername($username)
        ->setEmail($email)
        ->setImage("img/" . basename($image))
        ->setStateId($stateId)
        ->update($id);
    if (basename($user["image"]) !== basename($image)) ImageProcessor::deleteLastImage($user["image"]);
    $_SESSION["message"] = "User upload successly";
    Navigation::redirectTo("users.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Ángel Martínez Otero">
    <title>Update User</title>
    <script src="../scripts/previewImage.js"></script>
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50 dark:bg-gray-900 antialiased">
    <section class="bg-white dark:bg-gray-900">
        <div class="px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-2xl font-bold text-gray-900 dark:text-white">Update user</h2>
            <form action="<?= $_SERVER["PHP_SELF"] . "?id=$id" ?>" method="POST" enctype="multipart/form-data" novalidate>
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="text" name="email" id="email" value="<?= $user['email'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="User email" required="">
                        <?= SessionErrorDisplay::displayError("email") ?>
                    </div>
                    <div class="w-full">
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="text" name="username" id="username" value="<?= $user['username'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="User username" required="">
                        <?= SessionErrorDisplay::displayError("username") ?>
                    </div>
                    <div>
                        <label for="stateId" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">State</label>
                        <select id="stateId" name="stateId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option>Select state</option>
                            <?php foreach (State::readStates() as $state):
                                $selected = ($state["id"] === $user["state_id"]) ? ' selected=""' : "";
                            ?>
                                <option value="<?= $state['id'] ?>" <?= $selected ?>><?= $state['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                        <?= SessionErrorDisplay::displayError("stateId") ?>
                    </div>
                    <div>
                        <div class="flex items-center space-x-6">
                            <div class="shrink-0">
                                <img id='preview_img' class="h-16 w-16 object-cover rounded-full" src="<?= $user["image"] ?>" alt="Current profile photo" />
                            </div>
                            <label class="block">
                                <span class="sr-only">Choose profile photo</span>
                                <input type="file" accept="image/*" id="image" name="image" class="block w-full text-sm text-slate-500
                                                      file:mr-4 file:py-2 file:px-4
                                                      file:rounded-full file:border-0
                                                      file:text-sm file:font-semibold
                                                      file:bg-violet-50 file:text-violet-700
                                                      hover:file:bg-violet-100
                                                    " oninput="handlerImagePreview(this, 'preview_img');" />
                            </label>
                        </div>
                        <div>
                            <button class="ml-14 text-red-600 hover:text-red-700" name="resetDefaultImage">
                                <i class="fa-solid fa-trash fa-xs"></i>
                            </button>
                        </div>
                    </div>
                    <?= SessionErrorDisplay::displayError("image") ?>
                </div>
                <div class="mt-12">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                        Update user
                    </button>
                    <button type="reset" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-yellow-700 rounded-lg focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900 hover:bg-yellow-800 ml-4">
                        Reset
                    </button>
                    <a href="users.php" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-red-700 rounded-lg focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 hover:bg-red-800 ml-4">
                        Back
                    </a>
                </div>
            </form>
        </div>
    </section>
</body>

</html>