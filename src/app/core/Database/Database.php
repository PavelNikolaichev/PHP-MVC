<?php

namespace App\Core\Database;

use PDO;

class Database
{
    private PDO $pdo;

    /**
     * Constructor for Database.
     * All settings are loaded from .env file. Don't forget to load it!
     */
    public function __construct()
    {
        $this->pdo = new PDO($_ENV['DB_DSN'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Method to get PDO instance from Database class instance.
     * @return PDO - PDO instance.
     */
    final public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
