<?php

namespace App\Core\Database;

use App\Core\QueryBuilder;
use App\Models\UserModel;

class UserRepository
{
    private $queryBuilder;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Method to get all users from the database.
     * @return UserModel - an array of users.
     */
    final public function fetchAll(): UserModel
    {
        $data = $this->queryBuilder->fetch('users')->select(['email', 'name', 'gender', 'status', 'id'])->get()[0];

        return new UserModel(...$data);
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
            ->get();

        if (empty($data)) {
            return null;
        }

        return new UserModel(...$data[0]);
    }

    /**
     * @inheritDoc
     */
    final public function insert(array $data): bool
    {
        // TODO: Implement insert() method.
        throw new \Exception('Not implemented yet');
    }

    /**
     * @inheritDoc
     */
    final public function update(int $id, array $data): bool
    {
        // TODO: Implement update() method.'
        throw new \Exception('Not implemented yet');
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