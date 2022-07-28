<?php

namespace App\core\Database;

use App\Core\QueryBuilder;
use DateTime;
use Psr\Log\LoggerInterface;

class AttemptsRepository implements IAttemptsRepository
{
    public function __construct(private QueryBuilder $queryBuilder, private LoggerInterface $logger) {}

    public function isBanned(): bool
    {
        $data = $this->queryBuilder
            ->fetch('attempts')
            ->select(['*'])
            ->where('ip', '=', ip2long($this->getIP()))
            ->get();

        if (empty($data)) {
            return false;
        }

        return $data[0]['unbanned_at'] > time();
    }

    public function save(string|null $unbanned_at, int $attempts): array
    {
        return $this->queryBuilder
            ->fetch('attempts')
            ->insert([
                'ip' => ip2long($this->getIP()),
                'attempts' => $attempts,
                'unbanned_at' => $unbanned_at ?? null,
            ]);
    }

    public function timeLeft(): int
    {
        $data = $this->queryBuilder
            ->fetch('attempts')
            ->select(['*'])
            ->where('ip', '=', ip2long($this->getIP()))
            ->get();

        if (empty($data)) {
            return 0;
        }

        $time = strtotime($data[0]['unbanned_at']) - time();
        return max($time, 0);
    }

    public function incrementAttempt(): array
    {
        $data = $this->queryBuilder
            ->fetch('attempts')
            ->select(['*'])
            ->where('ip', '=', ip2long($this->getIP()))
            ->get();

        if (empty($data)) {
            return $this->queryBuilder
                ->fetch('attempts')
                ->insert([
                    'ip' => ip2long($this->getIP()),
                    'attempts' => 1,
                ]);
        }

        return $this->queryBuilder
            ->fetch('attempts')
            ->update(
                ['ip', ip2long($this->getIP())],
                ['attempts' => $data[0]['attempts'] + 1]
            );
    }

    public function update(string $unbanned_at, int $attempts): array
    {
        return $this->queryBuilder
            ->fetch('attempts')
            ->update(
                ['ip', ip2long($this->getIP())],
                ['unbanned_at' => $unbanned_at, 'attempts' => $attempts]
            );
    }

    public function fetch(): array|null
    {
        $data = $this->queryBuilder
            ->fetch('attempts')
            ->select(['*'])
            ->where('ip', '=', ip2long($this->getIP()))
            ->get();

        if (empty($data)) {
            return null;
        }

        return $data[0];
    }

    private function getIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }
}