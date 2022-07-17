<?php

namespace App\core\Services;

use App\core\Database\LoginRepository;
use App\models\LoginModel;

class RegistrationService
{
    public function __construct(private LoginRepository $repo) {}

    public function run(string $email, string $name, string $pass)
    {
        $model = new LoginModel($email, $name, $pass);

        $user = $this->repo->save($model);

        if ($user === null) {
            return new RegistrationEvent(false, null, 'Error during saving the model. Please try again.');
        }

        return new RegistrationEvent(
            $isPasswordsMatch, //true false
            $isPasswordsMatch ? $user : null, //set user if password valid
            $isPasswordsMatch ? null : 'Invalid credentials.'
        );
    }
}