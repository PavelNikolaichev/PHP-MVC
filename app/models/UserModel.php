<?php

namespace app\Models;

use App\Core\Model;

class UserModel extends Model
{
    /**
     * Method to get all users from the database.
     * @return array[] - an array of users.
     */
    final public function fetchAll(): array
    {
        return array(array('id' => 0, 'email' => 'zadaz@gmail.com', 'name' => 'Oleg', 'gender' => 'male', 'status' => 'active'),
        array('id' => 1, 'email' => 'mamba@gmail.com', 'name' => 'Tina', 'gender' => 'female', 'status' => 'inactive'));
//        return $this->db->query('SELECT * FROM users');
    }

    /**
     * Method to get one user from the database.
     * @return array - an array of user fields.
     */
    final public function fetch(): array
    {
        return array('id'=> 0, 'email' => 'zadaz@gmail.com', 'name' => 'Oleg', 'gender' => 'male', 'status' => 'active');
//        return $this->db->query('SELECT * FROM users WHERE id = ?', [$id]);
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