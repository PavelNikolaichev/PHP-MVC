<?php

namespace App\models;

use App\Core\Model;

class LoginModel extends Model
{
    public string $name;
    public string $email;
    public string $password;
    private array $errors = [];

    public function __construct(string $email, string $name, string $password)
    {
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
    }

    /**
     * Validation of input data.
     *
     * @return array|null - array of errors.
     */
    final public function validate(): array|null
    {
        foreach (get_object_vars($this) as $field => $value) {
            if (null === $value) {
                trigger_error("$field is empty!");

                return null;
            }
        }

        $this->validateName();
        $this->validateEmail();
        $this->validatePassword();

        return $this->errors;
    }

    /**
     * Validates name field.
     *
     * @return void
     */
    private function validateName(): void
    {
        if (empty($this->name)) {
            $this->addError('name', 'Name cannot be empty.');
        } elseif (!preg_match("/^([a-zA-Z\d' ]+)$/", $this->name)) {
            $this->addError('name', 'Name must contain letters or numbers without spaces.');
        }
    }

    /**
     * Validates email field.
     *
     * @return void
     */
    private function validateEmail(): void
    {
        if (empty($this->email)) {
            $this->addError('email', 'Email cannot be empty.');
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', 'Email must be a valid email.');
        }
    }

    public function validatePassword(): void
    {
        if (empty($this->password)) {
            $this->addError('password', 'Password cannot be empty.');
        } elseif (strlen($this->password) < 6) {
            $this->addError('password', 'Password must be at least 6 characters long.');
        }
    }

    /**
     * Adds an error into errors array.
     *
     * @param string $field - field that has an error.
     * @param string $error - error message.
     *
     * @return void
     */
    private function addError(string $field, string $error): void
    {
        $this->errors[$field] = $error;
    }
}