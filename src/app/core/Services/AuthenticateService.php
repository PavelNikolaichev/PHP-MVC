<?php

namespace App\core\Services;

use App\core\Database\IAttemptsRepository;
use App\core\Database\LoginRepository;

class AuthenticateService  {
    public function __construct(private LoginRepository $repo, private IAttemptsRepository $attemptsRepository) {}

    public function run($email, $pass): AuthenticateEvent
    {
        if ($this->attemptsRepository->isBanned()) {
            return new AuthenticateEvent(false, null, 'You are banned. Please try again after ' . $this->attemptsRepository->timeLeft() . ' seconds.');
        }

        $user = $this->repo->fetchByField('email', $email);

        if ($user === null) {
            return $this->incrementAttempt();
        }

        $isPasswordsMatch = password_verify($pass, $user->password);

        if (!$isPasswordsMatch) {
            return $this->incrementAttempt();
        }

        return new AuthenticateEvent(true, $user, null);
    }

    /**
     * @return AuthenticateEvent
     */
    private function incrementAttempt(): AuthenticateEvent
    {
        $this->attemptsRepository->incrementAttempt();

        $model = $this->attemptsRepository->fetch();

        if ($this->attemptsRepository->isBanned() || $model['attempts'] >= 3) {
            $this->attemptsRepository->update(date("Y-m-d H:i:s", strtotime("+15 minutes")), 0);
            return new AuthenticateEvent(false, null, 'You have failed 3 times. Please, try again after 15 minutes.');
        }


        return new AuthenticateEvent(false, null, 'Invalid credentials. You have ' . (3 - $model['attempts']) . ' attempts left.');
    }
}