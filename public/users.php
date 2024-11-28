<?php

use App\Database\UserEntity;
use App\Utils\AlertManager;

session_start();
require __DIR__ . "/../vendor/autoload.php";

$users = UserEntity::fetchUsers();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Ángel Martínez Otero">
    <title>Users</title>
    <!-- CDN SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
    <section>
        <div class="flex justify-center mt-8 mb-4">
            <h2 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                <span class="text-indigo-600">Users</span>
            </h2>
        </div>
        <div class="mx-auto max-w-screen-lg px-4 lg:px-12">
            <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex justify-end mx-4 py-4 border-t dark:border-gray-700">
                    <a href="new.php" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700">
                        <i class="fa-solid fa-plus mr-2"></i>Add User
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-gray-500 dark:text-gray-400">
                        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="p-4">ID</th>
                                <td class="p-4 font-bold">Username</td>
                                <th class="p-4">Email</th>
                                <th class="p-4">State</th>
                                <th class="p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <th class="px-4 py-3"><?= htmlspecialchars($user["id"]) ?></th>
                                    <th class="px-4 py-3 flex items-center">
                                        <img class="h-12 w-12 rounded-full mr-3" src="<?= htmlspecialchars($user["image"]) ?>" alt="<?= htmlspecialchars($user["username"]) ?>'s profile picture">
                                        <?= htmlspecialchars($user["username"]) ?>
                                    </th>
                                    <th class="px-4 py-3">
                                        <span class="bg-primary-100 text-primary-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-primary-900 dark:text-primary-300"><?= htmlspecialchars($user["email"]) ?></span>
                                    </th>
                                    <th class="px-4 py-3" style="color: <?= htmlspecialchars($user["stateColor"]) ?>"><?= htmlspecialchars($user["state"]) ?></th>
                                    <th class="px-4 py-3 flex items-center justify-center space-x-4">
                                        <a href="update.php?id=<?= $user["id"] ?>" class="text-sm text-white bg-green-700 hover:bg-green-800 font-medium rounded-lg px-3 py-2">
                                            <i class="fa-solid fa-pen-to-square mr-2"></i>Edit
                                        </a>
                                        <form action="delete.php" method="POST">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($user["id"]) ?>">
                                            <button type="submit" class="text-sm text-red-700 hover:text-white border border-red-700 hover:bg-red-800 font-medium rounded-lg px-3 py-2">
                                                Delete<i class="fa-solid fa-user-minus ml-2"></i>
                                            </button>
                                        </form>
                                    </th>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <?= AlertManager::showAlert() ?>
</body>

</html>