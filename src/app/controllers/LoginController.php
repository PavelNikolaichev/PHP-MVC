<?php

namespace App\controllers;

use App\Core\Controller;
use App\core\Database\ILoginRepository;
use App\core\IAuthenticatedUser;
use App\Core\IView;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;
use App\core\Services\AuthenticateService;
use App\core\Services\RegistrationService;
use Exception;
use RuntimeException;

class LoginController extends Controller
{
    public function __construct(
        ILoginRepository            $model,
        IView                       $view,
        private AuthenticateService $authenticateService,
        private RegistrationService      $registrationService,
        private IAuthenticatedUser  $sessionAuthenticatedUser
    ) {
        parent::__construct($model, $view);
    }

    public function login($params): IResponse
    {
        $data = [...$params];

        $data['user'] = $this->sessionAuthenticatedUser->getUser();

        $body = $this->view->render('login', $data);

        return new HTMLResponse(['200 OK'], $body);
    }

    public function loginPost($params): IResponse
    {
        $data = [];
        $headers = ['200 OK'];

        try {
            $data['user'] = $this->sessionAuthenticatedUser->getUser();

            if ($data['user'] !== null) {
                throw new RuntimeException('You are already logged in.');
            }

            // If there will be any errors, the exception will be thrown. Thus, the code below will not be executed.
            $model = $this->authenticateService->run($params['email'], $params['password']);

            if (isset($params['remember_me'])) {
                $token = md5(uniqid('', true));
                $this->model->saveToken($model->user->id, $token);
                setcookie('token', $token, time() + (86400 * 7), '/');
            }

            $_SESSION['email'] = $model->user->email;
            $_SESSION['user'] = $model->user->first_name;

            $data['user'] = ['email' => $model->user->email, 'name' => $model->user->first_name];
        } catch (Exception $e) {
            $data['errors'][] = $e->getMessage();
            $headers = ['400 Bad Request'];
        }

        $body = $this->view->render('login', $data);
        return new HTMLResponse($headers, $body);
    }

    public function logoutPost($params): IResponse
    {
        if (isset($_SESSION['user'])) {
            session_destroy();
        }

        if (isset($_COOKIE['token'])) {
            $this->model->deleteToken($_COOKIE['token']);
            setcookie('token', '', time() - (86400 * 7), '/');
        }

        return new HTMLResponse(['Location:/login'], $this->view->render('login', []));
    }

    public function register($params): IResponse
    {
        $data = [];

        $data['user'] = $this->sessionAuthenticatedUser->getUser();

        $body = $this->view->render('register', $data);

        return new HTMLResponse(['200 OK'], $body);
    }

    public function registerPOST($params): IResponse
    {
        $data = [];
        $serviceParams = array_intersect_key($params, array_flip(['email', 'first_name', 'last_name', 'password', 'password_confirmation']));

        try {
            $data['user'] = $this->sessionAuthenticatedUser->getUser();

            if ($data['user'] !== null) {
                throw new RuntimeException('You are already logged in.');
            }

            // If there will be any errors, the exception will be thrown. Thus, the code below will not be executed.
            $model = $this->registrationService->run(...$serviceParams);

            $_SESSION['email'] = $model->user->email;
            $_SESSION['user'] = $model->user->first_name;

            $data['user'] = ['email' => $model->user->email, 'name' => $model->user->first_name];
            $headers = ['Location:/login'];
        } catch (Exception $e) {
            $data['errors'][] = $e->getMessage();
            $headers = ['400 Bad Request'];
        }

        $body = $this->view->render('register', $data);
        return new HTMLResponse($headers, $body);
    }
}