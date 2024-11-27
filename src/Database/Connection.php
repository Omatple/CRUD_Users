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
    private ?PDO $connection = null;

    private function __construct()
    {
        $this->initializeInstance();
    }

    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new Connection;
        }
        return self::$instance;
    }

    private function initializeInstance(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();
        foreach (["USER", "PASSWORD", "HOST", "PORT", "DBNAME"] as $key) {
            if (!isset($_ENV[$key])) throw new Exception("Error processing enviroment variables");
        }
        $dsn = "mysql:dbname={$_ENV['DBNAME']};port={$_ENV['PORT']};host={$_ENV['HOST']};charset=utf8mb4";
        try {
            $this->connection = new PDO($dsn, $_ENV['USER'], $_ENV['PASSWORD'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException("Error connecting to database: {$e->getMessage()}", (int)$e->getCode());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function closeConnection(): void
    {
        $this->connection = null;
        self::$instance = null;
    }
}
