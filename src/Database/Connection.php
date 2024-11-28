<?php

namespace App\Database;

use \Dotenv\Dotenv;
use \Exception;
use \PDO;
use \PDOException;
use \RuntimeException;

require __DIR__ . "/../../vendor/autoload.php";
class Connection
{
    private static ?Connection $instance = null;
    private ?PDO $pdoConnection = null;

    private function __construct()
    {
        $this->initializeConnection();
    }

    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initializeConnection(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();

        foreach (["USER", "PASSWORD", "HOST", "PORT", "DBNAME"] as $envKey) {
            if (empty($_ENV[$envKey])) {
                throw new Exception("Missing required environment variable: {$envKey}");
            }
        }

        $dsn = sprintf(
            "mysql:dbname=%s;port=%s;host=%s;charset=utf8mb4",
            $_ENV["DBNAME"],
            $_ENV["PORT"],
            $_ENV["HOST"]
        );

        try {
            $this->pdoConnection = new PDO($dsn, $_ENV["USER"], $_ENV["PASSWORD"], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException("Failed to connect to the database: {$e->getMessage()}", (int)$e->getCode());
        }
    }

    public function getConnection(): PDO
    {
        if (is_null($this->pdoConnection)) {
            throw new RuntimeException("Database connection is not established.");
        }
        return $this->pdoConnection;
    }

    public function closeConnection(): void
    {
        $this->pdoConnection = null;
        self::$instance = null;
    }
}
