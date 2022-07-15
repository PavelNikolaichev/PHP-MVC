<?php

namespace App\core;

use App\models\LoginModel;
use Exception;

class AuthenticateEvent
{
    public function __construct(public bool $success, public ?LoginModel $user, public ?string $error)
    {
        if (!($success && $user !== null)) {
            throw new Exception($error);
        }

        $_SESSION['email'] = $user->email;
        $_SESSION['user'] = $user->name;
    }
}