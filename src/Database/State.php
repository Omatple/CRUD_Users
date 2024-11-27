<?php

namespace App\Database;

use \Faker\Factory;

require __DIR__ . "/../../vendor/autoload.php";
class State extends QueryExecutor
{
    private int $id;
    private string $name;
    private string $color;

    public static function readStates(): array
    {
        return parent::read("states");
    }

    public function createState(): void
    {
        parent::create("states", [
            ":n" => $this->name,
            ":c" => $this->color,
        ], "name", "color");
    }

    public function deleteState(int $id): void
    {
        parent::delete($id, "states");
    }

    public static function generateFakeStates(): void
    {
        $amount = 20;
        $faker = Factory::create("es_ES");
        for ($i = 0; $i < $amount; $i++) {
            (new State)
                ->setName($faker->unique()->state())
                ->setColor($faker->unique()->colorName())
                ->createState();
        }
    }

    public static function getStatesId(): array
    {
        $ids = [];
        $result = parent::executeQuery("SELECT id FROM states", "Failed retraiving IDs of states");
        while ($row = $result->fetchColumn()) {
            $ids[] = $row;
        }
        return $ids;
    }

    public static function deleteAllStates(): void
    {
        parent::deleteAll("states");
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
     * Get the value of name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of color
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Set the value of color
     */
    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
