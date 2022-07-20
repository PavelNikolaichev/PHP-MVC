<?php

namespace App\core\Services;

use App\core\Database\LoginRepository;
use App\models\LoginModel;

class RegistrationService
{
    public function __construct(private LoginRepository $repo) {}

    public function run(string $email, string $firstName, string $lastName, string $pass, string $confirmPass): RegistrationEvent
    {
        if ($pass !== $confirmPass) {
            return new RegistrationEvent(false, null, 'Passwords do not match.');
        }

        $model = new LoginModel($email, $firstName, $lastName, $pass);

        $errors = $model->validate();

        if (!empty($errors)) {
            return new RegistrationEvent(false, null, 'Please check your credentials and try again.' . print_r($errors, true));
        }

        $user = $this->repo->save($model);

        if ($user === null) {
            return new RegistrationEvent(false, null, 'Error during saving the model. Please try again.');
        }

        return new RegistrationEvent(true, $user, null);
    }
}