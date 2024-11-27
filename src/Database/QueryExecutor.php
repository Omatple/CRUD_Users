<?php

namespace App\Database;

use \Exception;
use \PDOException;
use \PDOStatement;

require __DIR__ . "/../../vendor/autoload.php";
class QueryExecutor
{
    protected static function executeQuery(string $query, ?string $customErrorMessage = null, ?array $placeholders = null): PDOStatement
    {
        $connection = Connection::getInstance();
        $pdo = $connection->getConnection()->prepare($query);
        try {
            $pdo->execute($placeholders ?? []);
            return $pdo;
        } catch (PDOException $e) {
            throw new Exception($customErrorMessage ?? "Error to execute query" . ": {$e->getMessage()}", (int) $e->getCode());
        } finally {
            $connection->closeConnection();
        }
    }

    protected static function delete(int $id, string $tableName): void
    {
        self::executeQuery("DELETE FROM $tableName WHERE id = :i", "Failed to delete from table '$tableName' with ID '$id'", [":i" => $id]);
    }

    protected static function create(string $tableName, array $placeholders, string ...$fieldName): void
    {
        self::executeQuery("INSERT INTO $tableName (" . implode(", ", $fieldName) . ") VALUES (" . implode(", ", array_keys($placeholders)) . ")", "Failed to create on table '$tableName'", $placeholders);
    }

    protected static function read(string $tableName, ?string $query = null): array
    {
        return self::executeQuery(is_null($query) ? "SELECT * FROM $tableName" : $query, "Failed to retraive $tableName")->fetchAll();
    }

    protected static function deleteAll(string $tableName): void
    {
        self::executeQuery("DELETE FROM $tableName", "Failed to delete all registres from $tableName");
        self::executeQuery("ALTER TABLE $tableName AUTO_INCREMENT = 1", "Failed reset auto increment of table $tableName");
    }

    protected static function isFieldUnique(string $tableName, string $field, string $value, ?int $id = null): bool
    {
        $excludeCurrentId = is_null($id) ? "" : " AND id <> :i";
        $placeholders = is_null($id) ? [":v" => $value] : [":v" => $value, ":i" => $id];
        return !self::executeQuery(
            "SELECT * FROM $tableName WHERE $field = :v$excludeCurrentId",
            "Failed to check if $field is unique on table $tableName",
            $placeholders
        )->fetchColumn();
    }

    protected static function getElementById(int $id, string $tableName): array|false
    {
        return self::executeQuery("SELECT * FROM $tableName WHERE id = :i", "Failed retraive element of table $tableName", [":i" => $id])->fetch();
    }
}
