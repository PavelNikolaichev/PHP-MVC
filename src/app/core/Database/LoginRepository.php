<?php

namespace App\core\Database;

use App\Core\Model;
use App\models\LoginModel;
use InvalidArgumentException;

class LoginRepository implements IRepository
{
    private array $users = [
        'user1@test.com' => [
        'name' => 'John',
        'password' => 'your_hash_here1',
        ],
        'user2@test.com' => [
        'name' => 'Jane',
        'password' => 'your_hash_here2',
        ],
    ];

    public function __construct()
    {
        foreach ($this->users as $email => $user) {
            $this->users[$email]['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        }
    }

    public function fetchAll(): array
    {
        $users = [];

        foreach ($this->users as $email => $user) {
            $users[] = new LoginModel($email, ...$user);
        }

        return $this->users;
    }

    public function fetch(mixed $id): Model|null
    {
        if (!is_string($id)) {
            throw new InvalidArgumentException('Fetch should have a string-class variable as input.');
        }

        return new LoginModel($id, ...$this->users[$id]) ?? null;
    }

    public function save(Model $model): Model|null
    {
        // TODO: Implement save() method.
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }
}