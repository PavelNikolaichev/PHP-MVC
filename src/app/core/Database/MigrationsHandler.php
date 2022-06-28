<?php

namespace App\core\Database;

use PDO;

class MigrationsHandler
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return void
     */
    public function applyMigrations(): void
    {
        $this->createMigrationsTable();

        $appliedMigrations = $this->getAppliedMigrations();

        $files = $this->getMigrationsFromDir($appliedMigrations);

        $newMigrations = $this->runMigrations($files);

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
            echo 'Migrations applied successfully' . PHP_EOL;
        } else {
            echo 'No new migrations found.' . PHP_EOL;
        }
    }

    /**
     * @return void
     */
    private function createMigrationsTable(): void
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS migrations (
            id INTEGER PRIMARY KEY AUTO_INCREMENT,
            migration VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
        )');
    }

    /**
     * @return array|bool
     */
    private function getAppliedMigrations(): array|bool
    {
        return $this->pdo->query('SELECT migration from migrations')->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * @param array $migrations
     *
     * @return void
     */
    private function saveMigrations(array $migrations): void
    {
        $migrations = array_map(function ($migration) {
            return [
                'migration' => $migration,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }, $migrations);

        $this->pdo->prepare('INSERT INTO migrations (migration, created_at) VALUES (:migration, :created_at)')->execute($migrations[0]);
    }

    /**
     * @param bool|array $appliedMigrations
     *
     * @return array|false
     */
    private function getMigrationsFromDir(bool|array $appliedMigrations): array|false
    {
        $files = scandir('app/migrations');

        return array_diff($files, array_merge(['.', '..'], $appliedMigrations));
    }

    /**
     * @param bool|array $files
     *
     * @return array
     */
    private function runMigrations(bool|array $files): array
    {
        $newMigrations = [];
        foreach ($files as $migration) {
            require_once 'app/migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();

            $instance->up($this->pdo);
            $newMigrations[] = $migration;
        }

        return $newMigrations;
    }
}
