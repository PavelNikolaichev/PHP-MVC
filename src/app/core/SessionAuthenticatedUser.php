<?php

namespace App\core;

use App\core\Database\LoginRepository;

class SessionAuthenticatedUser implements IAuthenticatedUser
{
    private array $user;

    public function __construct(private LoginRepository $repository)
    {
        if (isset($_SESSION['user'], $_SESSION['email'])) {
            $this->user = ['email' => $_SESSION['email'], 'name' => $_SESSION['user']];
        }

        if (isset($_COOKIE['token'])) {
            $user = $this->repository->fetchByToken($_COOKIE['token']);

            $_SESSION['user'] = $user->first_name;
            $_SESSION['email'] = $user->email;

            $this->user = ['email' => $user->email, 'name' => $user->first_name];
        }
    }

    public function getUser(): ?array
    {
        return $this->user ?? null;
    }
}