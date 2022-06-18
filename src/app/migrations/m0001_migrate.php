<?php

class m0001_migrate
{
    public function up(PDO $pdo): void
    {
        $pdo->exec('CREATE TABLE IF NOT EXISTS migrations (
            id INTEGER PRIMARY KEY AUTO_INCREMENT,
            migration VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
        )');
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE migrations');
    }
}