<?php

namespace App\core\Database;

use App\Core\Model;

interface IRepository
{
    public function fetchAll(): array;
    public function fetch(mixed $id): Model|null;
    public function save(Model $model): Model|null;
    public function delete(int $id): void;
}
