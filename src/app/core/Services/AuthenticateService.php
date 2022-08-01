<?php

namespace App\core\Services;

use App\core\Database\IAttemptsRepository;
use App\core\Database\LoginRepository;
use App\core\Log\LoginLogger;
use App\core\Traits\TraitIP;

class AuthenticateService  {
    use TraitIP;

    public function __construct(private LoginRepository $repo, private IAttemptsRepository $attemptsRepository, private LoginLogger $logger) {}

    public function run($email, $pass): AuthenticateEvent
    {
        if ($this->attemptsRepository->isBanned()) {
            return new AuthenticateEvent(false, null, 'You are banned. Please try again after ' . $this->attemptsRepository->timeLeft() . '.');
        }

        $user = $this->repo->fetchByField('email', $email);

        if ($user === null || !password_verify($pass, $user->password)) {
            $this->attemptsRepository->incrementAttempt();

            $model = $this->attemptsRepository->fetch();

            if ($this->attemptsRepository->isBanned() || $model['attempts'] >= 3) {
                $model = $this->attemptsRepository->update(date("Y-m-d H:i:s", strtotime("+15 minutes")), 0);

                $this->logger->info('User has been banned [{ip}, {first_attempt}, {unbanned_at}].', [
                    'ip' => $this->getIP(),
                    'first_attempt' => $model['first_attempt_at'],
                    'unbanned_at' => $model['unbanned_at']
                ]);

                return new AuthenticateEvent(false, null, 'You have failed 3 times. Please, try again after 15 minutes.');
            }


            return new AuthenticateEvent(false, null, 'Invalid credentials. You have ' . (3 - $model['attempts']) . ' attempts left.');
        }

        $this->attemptsRepository->delete();

        return new AuthenticateEvent(true, $user, null);
    }
}