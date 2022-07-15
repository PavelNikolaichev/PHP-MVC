<?php

namespace App\core;

use App\models\LoginModel;
use InvalidArgumentException;

class LoginForm
{
    public function __construct(private string $email, private string $username, private string $password) {}

    public function validate(): bool
    {
        $model = new LoginModel($this->email, $this->username, $this->password);
        $data['errors'] = $model->validate();

        if (!empty($data['errors'])) {
            $readableErrors = [];

            foreach ($data['errors'] as $field => $error) {
                $readableErrors[] = $field . ': ' . $error;
            }

            throw new InvalidArgumentException('Invalid form data: '. PHP_EOL . implode(PHP_EOL, $readableErrors));
        }

        return true;
    }

    public function getModel(): Model|null
    {
        return new LoginModel($this->email, $this->username, $this->password) ?? null;
    }
}