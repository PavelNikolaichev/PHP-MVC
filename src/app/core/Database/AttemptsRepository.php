<?php

namespace App\core\Database;

use App\core\Log\LoginLogger;
use App\Core\QueryBuilder;
use App\core\Traits\TraitIP;
use DateTime;

class AttemptsRepository implements IAttemptsRepository
{
    use TraitIP;

    public function __construct(private QueryBuilder $queryBuilder, private LoginLogger $logger) {}

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

        return $data[0]['unbanned_at'] > date('Y-m-d H:i:s');
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

    public function timeLeft(): string
    {
        $data = $this->queryBuilder
            ->fetch('attempts')
            ->select(['*'])
            ->where('ip', '=', ip2long($this->getIP()))
            ->get();

        if (empty($data)) {
            return 0;
        }

        $time = DateTime::createFromFormat('Y-m-d H:i:s', $data[0]['unbanned_at'])->diff(new DateTime('now'));
        return max($time->format('%h hours, %i minutes, %s seconds'), 0);
    }

    public function incrementAttempt(): array
    {
        $sql = "INSERT INTO attempts (ip, attempts) VALUES (:ip, :attempts) ON DUPLICATE KEY UPDATE attempts = attempts + 1";
        $this->queryBuilder->query(
            $sql,
            [
                'ip' => ip2long($this->getIP()),
                'attempts' => 1
            ]
        );

        return $this->queryBuilder
            ->fetch('attempts')
            ->select(['*'])
            ->where('ip', '=', ip2long($this->getIP()))
            ->get();
    }

    public function update(string $unbanned_at, int $attempts): array
    {
        $data = $this->queryBuilder
            ->fetch('attempts')
            ->update(
                ['ip', ip2long($this->getIP())],
                ['unbanned_at' => $unbanned_at, 'attempts' => $attempts]
            );

        if (!empty($data['unbanned_at'])) {
            $this->logger->info('User has been banned [{ip}, {first_attempt}, {unbanned_at}].', [
                'ip' => $this->getIP(),
                'first_attempt' => $data['first_attempt_at'],
                'unbanned_at' => $data['unbanned_at']
            ]);
        }

        return $data;
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
}