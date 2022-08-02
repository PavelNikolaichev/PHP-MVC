<?php

namespace App\core\Services;

use App\models\LoginModel;
use RuntimeException;

class RegistrationEvent
{
    public function __construct(public bool $success, public ?LoginModel $user, public ?string $error)
    {
        if (!($success && $user !== null)) {
            throw new RuntimeException($error);
        }
    }

}