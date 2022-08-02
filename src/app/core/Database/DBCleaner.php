<?php

namespace App\core\Database;

use PDO;

class DBCleaner
{
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function clean()
    {
//       Remove all outdated records from the sessions and attempts tables.
        $this->pdo->query('DELETE FROM sessions WHERE last_login + INTERVAL 7 DAY < NOW()');

        $this->pdo->query('DELETE FROM attempts WHERE first_attempt_at + INTERVAL 15 MINUTE < NOW() OR unbanned_at + INTERVAL 15 MINUTE < NOW()');
    }
}