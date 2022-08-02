<?php

namespace App\core;

use App\core\Database\LoginRepository;

class SessionAuthenticatedUser implements IAuthenticatedUser
{
    private array|null $user;

    public function __construct(private LoginRepository $repository)
    {
        if (isset($_SESSION['user'], $_SESSION['email'])) {
            $this->user = ['email' => $_SESSION['email'], 'name' => $_SESSION['user']];
        } elseif (isset($_COOKIE['token'])) {
            $user = $this->repository->fetchByToken($_COOKIE['token']);

            if (null === $user) {
                unset($_COOKIE['token']);
                setcookie('token', '', time() - (86400 * 7), '/');
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if ($user !== null) {
                $_SESSION['user'] = $user->first_name;
                $_SESSION['email'] = $user->email;
                $this->user = ['email' => $user->email, 'name' => $user->first_name];
            } else {
                $this->user = null;
            }
        }
    }

    public function getUser(): ?array
    {
        return $this->user ?? null;
    }
}