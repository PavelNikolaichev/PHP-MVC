<?php

namespace App\Core\Database;

use App\Core\Model;
use App\Core\QueryBuilder;
use App\Models\UserModel;

class UserRepository implements  IRepository
{
    private $queryBuilder;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Method to get all users from the database.
     * @return array - an array of users.
     */
    final public function fetchAll(): array
    {
        $records = $this->queryBuilder
            ->fetch('users')
            ->select(['*'])
            ->get();

        $users = [];

        foreach ($records as $record) {
            $users[] = new UserModel(...$record);
        }

        return $users;
    }

    /**
     * Method to get one user from the database.
     * @param int $id
     * @return UserModel|null - an array of user fields.
     */
    final public function fetch(int $id): UserModel|null
    {
        $data = $this->queryBuilder
            ->fetch('users')
            ->select(['id', 'email', 'name', 'gender', 'status'])
            ->where('id', '=', $id)
            ->get()[0];

        if (empty($data)) {
            return null;
        }

        return new UserModel(...$data);
    }

    final public function save(Model $model): Model
    {
        if (null === $model->id) {
            return new UserModel(...($this->queryBuilder
                ->fetch('users')
                ->insert(get_object_vars($model))));
        }

        $data = $this->queryBuilder
            ->fetch('users')
            ->update(get_object_vars($model));

        return new UserModel(...$data);
    }

    /**
     * @inheritDoc
     */
    final public function delete(int $id): bool
    {
        // TODO: Implement delete() method.
        throw new \Exception('Not implemented yet');
    }
}