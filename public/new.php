<?php

use App\Database\StateEntity;
use App\Database\UserEntity;
use App\Utils\ImageConfig;
use App\Utils\ImageHandler;
use App\Utils\Navigator;
use App\Utils\ErrorDisplay;
use App\Utils\UserValidator;

session_start();

require __DIR__ . "/../vendor/autoload.php";

if (isset($_POST["username"])) {
    $username = UserValidator::sanitizeInput($_POST["username"]);
    $email = UserValidator::sanitizeInput($_POST["email"]);
    $stateId = (int) UserValidator::sanitizeInput($_POST["stateId"]);
    $hasErrors = false;

    if (!UserValidator::validateUsername($username)) $hasErrors = true;
    if (!$hasErrors && !UserValidator::isUniqueField("username", $username)) $hasErrors = true;
    if (!UserValidator::validateEmail($email)) {
        $hasErrors = true;
    } elseif (!UserValidator::isUniqueField("email", $email)) {
        $hasErrors = true;
    }
    if (!UserValidator::validateStateId($stateId)) $hasErrors = true;

    if (ImageHandler::hasNoUploadError($_FILES["image"]["error"]) && !ImageHandler::isImageValid($_FILES["image"])) {
        $hasErrors = true;
    }

    if ($hasErrors) Navigator::refresh();

    $imagePath = ImageConfig::DEFAULT_IMAGE_FILENAME;
    if (
        ImageHandler::hasNoUploadError($_FILES["image"]["error"]) &&
        !ImageHandler::moveUploadedFile(
            $_FILES["image"]["tmp_name"],
            ($imagePath = ImageHandler::generateUniqueImagePath($_FILES["image"]["name"]))
        )
    ) Navigator::refresh();

    (new UserEntity)
        ->setUsername($username)
        ->setEmail($email)
        ->setImage("img/" . basename($imagePath))
        ->setStateId($stateId)
        ->addUser();

    $_SESSION["message"] = "User created successfully.";
    Navigator::redirect("users.php");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Ángel Martínez Otero">
    <title>New User</title>
    <script src="../scripts/imagePreview.js"></script>
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50 dark:bg-gray-900 antialiased">
    <section class="bg-white dark:bg-gray-900">
        <div class="px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-2xl font-bold text-gray-900 dark:text-white">Add a New User</h2>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" enctype="multipart/form-data" novalidate>
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter user email" required>
                        <?= ErrorDisplay::showError("email") ?>
                    </div>
                    <div class="w-full">
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter username" required>
                        <?= ErrorDisplay::showError("username") ?>
                    </div>
                    <div>
                        <label for="stateId" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">State</label>
                        <select id="stateId" name="stateId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                            <option value="" selected>Select a state</option>
                            <?php foreach (StateEntity::fetchStates() as $state): ?>
                                <option value="<?= htmlspecialchars($state['id']) ?>"><?= htmlspecialchars($state['name']) ?></option>
                            <?php endforeach ?>
                        </select>
                        <?= ErrorDisplay::showError("stateId") ?>
                    </div>
                    <div>
                        <div class="flex items-center space-x-6">
                            <div class="shrink-0">
                                <img id="preview_img" class="h-16 w-16 object-cover rounded-full" src="<?= "img/" . ImageConfig::DEFAULT_IMAGE_FILENAME ?>" alt="Profile preview">
                            </div>
                            <label class="block">
                                <span class="sr-only">Choose profile photo</span>
                                <input type="file" accept="image/*" id="image" name="image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" oninput="handleImagePreview(this, 'preview_img');">
                            </label>
                        </div>
                    </div>
                </div>
                <?= ErrorDisplay::showError("image") ?>
                <div class="mt-12">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800"><i class="fa-solid fa-user-plus mr-2"></i>Add User</button>
                    <button type="reset" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-yellow-700 rounded-lg focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900 hover:bg-yellow-800 ml-4"><i class="fa-regular fa-window-restore mr-2"></i>Reset</button>
                    <a href="users.php" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-red-700 rounded-lg focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 hover:bg-red-800 ml-4"><i class="fa-solid fa-backward mr-2"></i>Back</a>
                </div>
            </form>
        </div>
    </section>
</body>

</html>