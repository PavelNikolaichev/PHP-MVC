<?php

namespace App\Models;

use App\Core\Model;

class UserModel extends Model
{
    public string $name;
    public string $email;
    public ?int $id;
    public string $gender;
    public string $status;
    private array $errors = [];

    public function __construct(string $email, string $name, string $gender, string $status, int $id = null)
    {
        $this->email = $email;
        $this->name = $name;
        $this->gender = $gender;
        $this->status = $status;
        $this->id = $id;
    }

    /**
     * Validation of input data.
     *
     * @return array|null - array of errors.
     */
    final public function validate(): array|null
    {
        foreach (get_object_vars($this) as $field => $value) {
            if (null === $value && $field !== 'id') {
                trigger_error("$field is empty!");

                return null;
            }
        }

        $this->validateName();
        $this->validateEmail();
        $this->validateGender();
        $this->validateStatus();

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
        } elseif (!preg_match("/^([a-zA-Z\d' ]+) ([a-zA-Z\d' ]+)$/", $this->name)) {
            $this->addError('name', 'Name must contain letters or numbers with space.');
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

    /**
     * Validates gender field.
     *
     * @return void
     */
    private function validateGender(): void
    {
        $genders = [
            'male',
            'female',
        ];

        if (!in_array($this->gender, $genders)) {
            $this->addError('gender', 'Gender should be either Male or Female.');
        }
    }

    /**
     * Validates status field.
     *
     * @return void
     */
    private function validateStatus(): void
    {
        $statuses = [
            'active',
            'inactive',
        ];

        if (!in_array($this->status, $statuses)) {
            $this->addError('status', 'Status should be either active or inactive.');
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
