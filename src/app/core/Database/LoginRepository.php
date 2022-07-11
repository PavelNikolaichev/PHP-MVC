<?php

namespace App\core\Database;

use App\Core\Model;
use App\models\LoginModel;

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

    public function fetchAll(): array
    {
        $users = [];

        foreach ($this->users as $user) {
            $users[] = new LoginModel(...$user);
        }

        return $this->users;
    }

    public function fetch(int $id): Model|null
    {
        return new LoginModel(...$this->users[$id]) ?? null;
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