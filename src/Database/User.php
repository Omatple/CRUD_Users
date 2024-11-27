<?php

namespace App\Database;

use App\Utils\ImageConstants;
use \Faker\Factory;
use \Mmo\Faker\FakeimgProvider;

require __DIR__ . "/../../vendor/autoload.php";
class User extends QueryExecutor
{
    private int $id;
    private string $username;
    private string $email;
    private string $image;
    private int $state_id;

    public static function readUsers(): array
    {
        return parent::read("users", "SELECT users.*, name as state, color as stateColor FROM users, states WHERE state_id = states.id ORDER BY name");
    }

    public function createUser(): void
    {
        parent::create("users", [
            ":u" => $this->username,
            ":e" => $this->email,
            ":i" => $this->image,
            ":s" => $this->state_id,
        ], "username, email, image, state_id");
    }

    public static function generateFakeUsers(int $amount): void
    {
        $faker = Factory::create("es_ES");
        $faker->addProvider(new FakeimgProvider($faker));
        for ($i = 0; $i < $amount; $i++) {
            $username = $faker->unique()->userName();
            (new User)
                ->setUsername($username)
                ->setEmail($username . "@" . $faker->freeEmailDomain())
                ->setImage($faker->fakeImg(dir: __DIR__ . "/../../public/img/", width: 640, height: 640, fullPath: false, text: strtoupper(substr($username, 0, 3)), backgroundColor: [random_int(0, 255), random_int(0, 255), random_int(0, 255)]))
                ->setStateId($faker->randomElement(State::getStatesId()))
                ->createUser();
        }
    }

    public static function deleteAllUsers(): void
    {
        parent::deleteAll("users");
    }

    public static function isFieldUniqueUsers(string $field, string $value, ?int $id = null): bool
    {
        return parent::isFieldUnique("users", $field, $value, $id);
    }

    public static function llamame(): bool
    {
        return parent::isFieldUnique("users", "username", "andrea.saenz");
    }

    public static function  getImageById(int $id): string|false
    {
        return parent::executeQuery("SELECT image FROM users WHERE id = :i", "Failed retraiving image of user with ID '$id'", [":i" => $id])->fetchColumn();
    }

    public static function deleteUser(int $id): void
    {
        parent::delete($id, "users");
    }

    public function update(int $id): void
    {
        parent::executeQuery("UPDATE users SET username = :u, email = :e, image = :im, state_id = :s WHERE id = :i", "Failed to update user with ID '$id'", [
            ":i" => $id,
            ":u" => $this->username,
            ":e" => $this->email,
            ":im" => $this->image,
            ":s" => $this->state_id,
        ]);
    }

    public static function resetImageDefault(int $id): void
    {
        parent::executeQuery("UPDATE users SET image = :im WHERE id = :i", "Failed reset image of user with ID '$id'", [
            ":im" => "img/" . ImageConstants::IMAGE_DEFAULT_FILENAME,
            ":i" => $id,
        ]);
    }

    public static function getUserById(int $id): array|false
    {
        return parent::getElementById($id, "users");
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of image
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Set the value of image
     */
    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get the value of state_id
     */
    public function getStateId(): int
    {
        return $this->state_id;
    }

    /**
     * Set the value of state_id
     */
    public function setStateId(int $state_id): self
    {
        $this->state_id = $state_id;

        return $this;
    }
}
