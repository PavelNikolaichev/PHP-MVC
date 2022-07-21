<?php

namespace App\core\Services;

use App\core\Database\LoginRepository;
use App\core\Forms\RegistrationFormValidator;
use App\models\LoginModel;

class RegistrationService
{
    public function __construct(private LoginRepository $repo, ) {}

    public function run(string $email, string $first_name, string $last_name, string $password, string $password_confirmation): RegistrationEvent
    {
        $validator = new RegistrationFormValidator(
            $this->repo->getLogger(),
            [
                'email' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'password' => $password,
                'confirm_password' => $password_confirmation,
            ],
        );

        if (!$validator->isValid()) {
            return new RegistrationEvent(false, null, 'Please check your credentials and try again.' . print_r($validator->getErrors(), true));
        }

        $model = new LoginModel($email, $first_name, $last_name, $password);

        $user = $this->repo->save($model);

        if ($user === null) {
            return new RegistrationEvent(false, null, 'Error during saving the model. Please try again.');
        }

        return new RegistrationEvent(true, $user, null);
    }
}