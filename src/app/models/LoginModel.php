<?php

namespace App\models;

use App\Core\Model;

class LoginModel extends Model
{
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $password;
    public ?int $id;
    private array $errors = [];

    public function __construct(string $email, string $first_name, string $last_name, string $password, int $id = null)
    {
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->password = $password;
        $this->id = $id;
    }

    /**
     * Validation of input data.
     *
     * @return array - array of errors.
     */
    final public function validate(): array
    {
        $this->validateName($this->first_name);
        $this->validateName($this->last_name);
        $this->validateEmail();
        $this->validatePassword();

        return $this->errors;
    }

    /**
     * Validates name field.
     *
     * @param $name - name to validate.
     * @return void
     */
    private function validateName($name): void
    {
        if (empty($name)) {
            $this->addError('name', 'Name cannot be empty.');
        } elseif (!preg_match("/^([a-zA-Z\d']+)$/", $name)) {
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
        } elseif (!preg_match("/^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*\d)(?=\S*\W)\S*$/", $this->password)) {
            $this->addError('password', 'Password must be at least 6 characters long and contain at least 1 capital letter, 1 special character and 1 digit.');
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