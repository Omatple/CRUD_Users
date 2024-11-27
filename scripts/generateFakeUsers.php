<?php

use App\Database\State;
use App\Database\User;

require __DIR__ . "/../vendor/autoload.php";
do {
    $amount = (int)readline("Write amount of fakes users you want generate(5-50), or '0' for exit: ");
    if ($amount === 0) exit("\nExit to request of user..." . PHP_EOL);
    if ($amount < 5 || $amount > 50) echo "\nERROR: Please write a number between 5 and 50 includes." . PHP_EOL;
} while ($amount < 5 || $amount > 50);

User::deleteAllUsers();
State::deleteAllStates();
State::generateFakeStates();
User::generateFakeUsers($amount);
echo "\n$amount fake users been generate." . PHP_EOL;
