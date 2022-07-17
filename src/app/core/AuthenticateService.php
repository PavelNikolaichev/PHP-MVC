<?php

namespace App\core;

use App\core\Database\LoginRepository;

class AuthenticateService  {
    public function __construct(private LoginRepository $repo) {}

    public function run($email, $pass)
    {
        $user = $this->repo->fetch($email);

        if ($user === null) {
            return new AuthenticateEvent(false, null, 'Invalid credentials.');
        }

        $isPasswordsMatch = password_verify($pass, $user->password);

        return new AuthenticateEvent(
            $isPasswordsMatch, //true false
            $isPasswordsMatch ? $user : null, //set user if password valid
            $isPasswordsMatch ? null : 'Invalid credentials.'
        );

    }
}