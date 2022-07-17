<?php

namespace App\Core\Database;

use App\Core\Model;
use App\Core\QueryBuilder;
use App\Models\UserModel;
use InvalidArgumentException;

class UserRepository implements IRepository
{
    public function __construct(private QueryBuilder $queryBuilder) {}

    /**
     * Method to get all users from the database.
     *
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
     *
     * @param int $id
     *
     * @return UserModel|null - an array of user fields.
     */
    final public function fetch(mixed $id): UserModel|null
    {
        if (!is_int($id)) {
            throw new InvalidArgumentException('Fetch should have an int-class variable as input.');
        }

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
     * Method to save or update a user to the database. The behavior is determined by the presence of the id field.
     *
     * @param Model $model - Model instance to save.
     *
     * @return Model
     */
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
     * Method to delete a user from the database.
     *
     * @param int $id - id of the user to be deleted.
     */
    final public function delete(int $id): void
    {
        $this->queryBuilder->fetch('users')->delete($id);
    }
}
