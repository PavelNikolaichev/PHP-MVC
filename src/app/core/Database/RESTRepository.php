<?php

namespace App\Core\Database;

use App\core\CurlManager;
use App\Core\Model;
use App\Models\UserModel;

class RESTRepository implements IRepository
{
    private string $url = "https://gorest.co.in/public/v2/";
    private CurlManager $curl;

    public function __construct(CurlManager $curl)
    {
        $this->curl = $curl;
    }

    /**
     * Method to get all users from the database.
     *
     * @return array - an array of users.
     */
    final public function fetchAll(): array
    {
        $records = $this->curl->get($this->url . 'users');
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
    final public function fetch(int $id): UserModel|null
    {
        $data = $this->curl->get($this->url . 'users/' . $id);

        if (empty($data)) {
            return null;
        }

        return new UserModel(...$data);
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
        $data = (null === $model->id)
            ? $this->curl->post($this->url . 'users', json_encode(get_object_vars($model)))
            : $this->curl->patch($this->url . 'users/' . $model->id, json_encode(get_object_vars($model)));

        return new UserModel(...$data);
    }

    /**
     * Method to delete a user from the database.
     *
     * @param int $id - id of the user to be deleted.
     */
    final public function delete(int $id): void
    {
        $this->curl->delete($this->url . 'users/' . $id);;
    }
}