<?php

namespace App\Database;

use App\Utils\ImageConfig;
use App\Utils\ImageConstants;
use \Faker\Factory;
use \Mmo\Faker\FakeimgProvider;

require __DIR__ . "/../../vendor/autoload.php";

class UserEntity extends DatabaseQueryHandler
{
    private int $id;
    private string $username;
    private string $email;
    private string $image;
    private int $stateId;

    public static function fetchUsers(): array
    {
        return parent::fetchRecords("users", "SELECT users.*, name as state, color as stateColor FROM users, states WHERE state_id = states.id ORDER BY name");
    }

    public function addUser(): void
    {
        parent::insertRecord("users", [
            ":username" => $this->username,
            ":email" => $this->email,
            ":image" => $this->image,
            ":stateId" => $this->stateId,
        ], "username, email, image, state_id");
    }

    public static function generateFakeUsers(int $count): void
    {
        $faker = Factory::create("es_ES");
        $faker->addProvider(new FakeimgProvider($faker));
        for ($i = 0; $i < $count; $i++) {
            $username = $faker->unique()->userName();
            (new UserEntity)
                ->setUsername($username)
                ->setEmail($username . "@" . $faker->freeEmailDomain())
                ->setImage("img/" . $faker->fakeImg(dir: __DIR__ . "/../../public/img/", width: 640, height: 640, fullPath: false, text: strtoupper(substr($username, 0, 3)), backgroundColor: [random_int(0, 255), random_int(0, 255), random_int(0, 255)]))
                ->setStateId($faker->randomElement(StateEntity::fetchStateIds()))
                ->addUser();
        }
    }

    public static function clearAllUsers(): void
    {
        parent::clearTable("users");
    }

    public static function isUserFieldUnique(string $field, string $value, ?int $excludeId = null): bool
    {
        return parent::isUniqueField("users", $field, $value, $excludeId);
    }

    public static function fetchImageById(int $id): string|false
    {
        return parent::runQuery("SELECT image FROM users WHERE id = :id", "Failed to retrieve image of user with ID '$id'", [":id" => $id])->fetchColumn();
    }

    public static function removeUser(int $id): void
    {
        parent::removeRecord($id, "users");
    }

    public function updateUser(int $id): void
    {
        parent::runQuery("UPDATE users SET username = :username, email = :email, image = :image, state_id = :stateId WHERE id = :id", "Failed to update user with ID '$id'", [
            ":id" => $id,
            ":username" => $this->username,
            ":email" => $this->email,
            ":image" => $this->image,
            ":stateId" => $this->stateId,
        ]);
    }

    public static function resetDefaultImage(int $id): void
    {
        parent::runQuery("UPDATE users SET image = :image WHERE id = :id", "Failed to reset image of user with ID '$id'", [
            ":image" => "img/" . ImageConfig::DEFAULT_IMAGE_FILENAME,
            ":id" => $id,
        ]);
    }

    public static function fetchUserById(int $id): array|false
    {
        return parent::getRecordById($id, "users");
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
     * Get the value of stateId
     */
    public function getStateId(): int
    {
        return $this->stateId;
    }

    /**
     * Set the value of stateId
     */
    public function setStateId(int $stateId): self
    {
        $this->stateId = $stateId;

        return $this;
    }
}
