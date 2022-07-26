<?php

namespace App\core\Database;

use App\Core\Model;
use App\Core\QueryBuilder;
use App\models\LoginModel;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

class LoginRepository implements IRepository
{
    public function __construct(private QueryBuilder $queryBuilder, private LoggerInterface $logger) {}

    public function fetchAll(): array
    {
        $records = $this->queryBuilder
            ->fetch('logins')
            ->select(['*'])
            ->get();

        $users = [];

        foreach ($records as $record) {
            $users[] = new LoginModel(...$record);
        }

        return $users;
    }

    public function fetch(mixed $id): LoginModel|null
    {
        if (!is_int($id)) {
            throw new InvalidArgumentException('Fetch should have an int-class variable as input.');
        }

        $data = $this->queryBuilder
            ->fetch('logins')
            ->select(['*'])
            ->where('id', '=', $id)
            ->get();

        if (empty($data)) {
            return null;
        }

        return new LoginModel(...$data[0]);
    }

    public function fetchByField(string $field, string $value): LoginModel|null
    {
        $data = $this->queryBuilder
            ->fetch('logins')
            ->select(['*'])
            ->where($field, '=', $value)
            ->get();

        if (empty($data)) {
            return null;
        }

        unset($data[0]['created_date']);

        return new LoginModel(...$data[0]);
    }

    public function save(Model $model): LoginModel|null
    {
        if ($model->id !== null) {
            throw new InvalidArgumentException('Cannot save an existing model.');
        }

        if (null !== ($this->fetchByField('email', $model->email))) {
            throw new InvalidArgumentException('This email is already in use.');
        }

        $saveData = get_object_vars($model);
        $saveData['password'] = password_hash($saveData['password'], PASSWORD_DEFAULT);
        unset($saveData['id']);

        $data = ($this->queryBuilder
            ->fetch('logins')
            ->insert($saveData));

        unset($data['created_date']);

        return new LoginModel(...$data);
    }


    /**
     * Method to delete a user from the database.
     *
     * @param int $id - id of the user to be deleted.
     */
    final public function delete(int $id): void
    {
        $this->queryBuilder->fetch('logins')->delete($id);
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function fetchByToken(string $token)
    {
        // TODO: make a leftJoin and LoginModel construction. See where can I get user ip.
        $data = $this->queryBuilder
            ->fetch('sessions')
            ->select(['*'])
            ->where('token', '=', $token)
            ->andWhere('expiration_date', '>', date('Y-m-d H:i:s'))
            ->andWhere('user_ip', '=', $_SERVER['REMOTE_ADDR'])
            ->leftJoin('logins', ['sessions.user_id as user_id'], '=', 'logins.id')
            ->get();

        if (empty($data)) {
            return null;
        }

        return new LoginModel(...$data[0]);
    }

    public function saveToken(int $id, string $token, $ip)
    {
        $data = $this->queryBuilder
            ->fetch('sessions')
            ->insert(['id' => $id, 'token' => $token, 'user_ip' => $ip]);

        if (empty($data)) {
            return null;
        }

        return new LoginModel(...$data[0]);
    }

    public function deleteToken(int $id)
    {
        // TODO: delete by token instead of id
        $this->queryBuilder->fetch('sessions')->delete($id);
    }
}