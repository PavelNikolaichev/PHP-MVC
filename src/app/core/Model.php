<?php

namespace App\Core;

abstract class Model
{
    /**
     * Abstract method to get all rows from the database.
     * @return array[]
     */
    abstract public function fetchAll(): array;

    /**
     * Abstract method to get one row from the database.
     * @return array
     */
    abstract public function fetch(): array;

    /**
     * Abstract method to insert a new row into the database.
     * @param array $data - The data to create a new row.
     * @return bool - True if the row was created, false otherwise.
     */
    abstract public function insert(array $data): bool;

    /**
     * Abstract method to update a row in the database.
     * @oaram int $id - The id of the row to update.
     * @param array $data - The data to update a row.
     * @return bool - True if the row was updated, false otherwise.
     */
    abstract public function update(int $id, array $data): bool;

    /**
     * Abstract method to delete a row from the database.
     * @param int $id - The id of the row to delete.
     * @return bool - True if the row was deleted, false otherwise.
     */
    abstract public function delete(int $id): bool;
}