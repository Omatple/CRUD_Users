<?php

use App\Database\StateEntity;
use App\Database\UserEntity;

require __DIR__ . "/../vendor/autoload.php";

do {
    $userCount = (int)readline("Enter the number of fake users to generate (5-50), or '0' to exit: ");
    if ($userCount === 0) exit("\nExiting as requested by the user..." . PHP_EOL);
    if ($userCount < 5 || $userCount > 50) echo "\nERROR: Please enter a number between 5 and 50 (inclusive)." . PHP_EOL;
} while ($userCount < 5 || $userCount > 50);

UserEntity::clearAllUsers();
StateEntity::clearAllStates();
StateEntity::generateFakeStates();
UserEntity::generateFakeUsers($userCount);
echo "\n$userCount fake users have been generated." . PHP_EOL;
