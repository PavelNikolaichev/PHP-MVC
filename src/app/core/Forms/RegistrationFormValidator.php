<?php

namespace App\core\Forms;

use App\models\LoginModel;
use Psr\Log\LoggerInterface;

class RegistrationFormValidator
{
    private array $errors = [];

    public function __construct(private LoggerInterface $logger, private array $formData) {}

    public function isValid(): bool
    {
        if ($this->formData['password'] !== $this->formData['confirm_password']) {
            $this->errors['password'] = 'Passwords do not match.';
            $this->logger->error('Passwords do not match.');
            return false;
        }

        $model = new LoginModel($this->formData['email'], $this->formData['first_name'], $this->formData['last_name'], $this->formData['password']);
        $errors = $model->validate();

        if (!empty($errors)) {
            $this->errors = array_merge($this->errors, $errors);
            $this->logger->error('Please check your credentials and try again.' . print_r($errors, true));
            return false;
        }

        return true;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}