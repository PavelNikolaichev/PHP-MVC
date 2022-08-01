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
        // TODO: Mb we can fetch with a time condition, so we could rid of the time checks below.
        $data = $this->queryBuilder
            ->fetch('attempts')
            ->select(['*'])
            ->where('ip', '=', ip2long($this->getIP()))
            ->get();

        if (empty($data)) {
            return false;
        }

        // TODO: get rid of hardcoded values.
        $att_time = DateTime::createFromFormat('Y-m-d H:i:s', $data[0]['first_attempt_at'])->modify('+15 minutes');

        if ($att_time < new DateTime('now')) {
            $this->update(null, 0, true);
        }

        return $data[0]['unbanned_at'] > date('Y-m-d H:i:s') && $att_time > date("Y-m-d H:i:s");
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

    public function update(string|null $unbanned_at, int $attempts, bool $set_attempt=false): array
    {
        $params = [
            'unbanned_at' => $unbanned_at ?? null,
            'attempts' => $attempts,
        ];

        if ($set_attempt) {
            $params['first_attempt_at'] = date("Y-m-d H:i:s");
        }

        return $this->queryBuilder
            ->fetch('attempts')
            ->update(['ip', ip2long($this->getIP())], $params);
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

    public function delete(): void
    {
        $this->queryBuilder
            ->fetch('attempts')
            ->delete('ip', ip2long($this->getIP()));
    }
}