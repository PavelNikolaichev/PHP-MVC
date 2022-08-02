<?php

namespace App\core\Database;

use App\models\LoginModel;

interface ILoginRepository extends IRepository
{
    public function saveToken(int $id, string $token): ?LoginModel;
    public function fetchByField(string $field, string $value): LoginModel|null;
    public function fetchByToken(string $token): ?LoginModel;
}