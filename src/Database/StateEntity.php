<?php

namespace App\Database;

use \Faker\Factory;

require __DIR__ . "/../../vendor/autoload.php";

class StateEntity extends DatabaseQueryHandler
{
    private int $id;
    private string $name;
    private string $color;

    public static function fetchStates(): array
    {
        return parent::fetchRecords("states");
    }

    public function addState(): void
    {
        parent::insertRecord("states", [
            ":name" => $this->name,
            ":color" => $this->color,
        ], "name", "color");
    }

    public function removeState(int $id): void
    {
        parent::removeRecord($id, "states");
    }

    public static function generateFakeStates(): void
    {
        $count = 20;
        $faker = Factory::create("es_ES");
        for ($i = 0; $i < $count; $i++) {
            (new StateEntity)
                ->setName($faker->unique()->state())
                ->setColor($faker->unique()->colorName())
                ->addState();
        }
    }

    public static function fetchStateIds(): array
    {
        $ids = [];
        $result = parent::runQuery("SELECT id FROM states", "Failed to retrieve IDs of states");
        while ($row = $result->fetchColumn()) {
            $ids[] = $row;
        }
        return $ids;
    }

    public static function clearAllStates(): void
    {
        parent::clearTable("states");
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
