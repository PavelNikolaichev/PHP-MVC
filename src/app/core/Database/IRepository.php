<?php

namespace App\core\Database;

use App\Core\Model;

interface IRepository
{
    public function fetchAll(): array;
    public function fetch(int $id): Model|null;
    public function save(Model $model): Model;
    public function delete(int $id): void;
}
