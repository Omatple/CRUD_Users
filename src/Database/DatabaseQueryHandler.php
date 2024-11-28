<?php

namespace App\Database;

use \Exception;
use \PDOException;
use \PDOStatement;

require __DIR__ . "/../../vendor/autoload.php";

class DatabaseQueryHandler
{
    protected static function runQuery(string $sql, ?string $errorMessage = null, ?array $bindings = null): PDOStatement
    {
        $dbConnection = Connection::getInstance();
        $preparedStatement = $dbConnection->getConnection()->prepare($sql);
        try {
            $preparedStatement->execute($bindings ?? []);
            return $preparedStatement;
        } catch (PDOException $e) {
            throw new Exception($errorMessage ?? "Query execution failed: {$e->getMessage()}", (int) $e->getCode());
        } finally {
            $dbConnection->closeConnection();
        }
    }

    protected static function removeRecord(int $id, string $table): void
    {
        self::runQuery("DELETE FROM $table WHERE id = :id", "Failed to delete from table '$table' with ID '$id'", [":id" => $id]);
    }

    protected static function insertRecord(string $table, array $bindings, string ...$fields): void
    {
        self::runQuery("INSERT INTO $table (" . implode(", ", $fields) . ") VALUES (" . implode(", ", array_keys($bindings)) . ")", "Failed to insert into table '$table'", $bindings);
    }

    protected static function fetchRecords(string $table, ?string $sql = null): array
    {
        return self::runQuery(is_null($sql) ? "SELECT * FROM $table" : $sql, "Failed to retrieve records from $table")->fetchAll();
    }

    protected static function clearTable(string $table): void
    {
        self::runQuery("DELETE FROM $table", "Failed to delete all records from $table");
        self::runQuery("ALTER TABLE $table AUTO_INCREMENT = 1", "Failed to reset auto-increment of table $table");
    }

    protected static function isUniqueField(string $table, string $column, string $value, ?int $excludeId = null): bool
    {
        $excludeCondition = is_null($excludeId) ? "" : " AND id <> :excludeId";
        $bindings = is_null($excludeId) ? [":value" => $value] : [":value" => $value, ":excludeId" => $excludeId];
        return !self::runQuery(
            "SELECT * FROM $table WHERE $column = :value$excludeCondition",
            "Failed to check uniqueness of $column in table $table",
            $bindings
        )->fetchColumn();
    }

    protected static function getRecordById(int $id, string $table): array|false
    {
        return self::runQuery("SELECT * FROM $table WHERE id = :id", "Failed to retrieve record from table $table", [":id" => $id])->fetch();
    }
}
