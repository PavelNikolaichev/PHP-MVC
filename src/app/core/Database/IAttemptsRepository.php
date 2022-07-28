<?php

namespace App\core\Database;

use DateTime;

interface IAttemptsRepository
{
    public function isBanned(): bool;
    public function save(string|null $unbanned_at, int $attempts): array;
    public function timeLeft(): int;
    public function incrementAttempt(): array;
    public function fetch(): array|null;
}