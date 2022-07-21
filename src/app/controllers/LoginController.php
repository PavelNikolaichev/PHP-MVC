<?php

namespace App\controllers;

use App\Core\Controller;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;
use App\core\Services\AuthenticateService;
use App\core\Services\RegistrationService;
use Exception;

class LoginController extends Controller
{
    public function login($params): IResponse
    {
        $data = [...$params];

        if (isset($_SESSION['user'], $_SESSION['email'])) {
            $data['user'] = ['email' => $_SESSION['email'], 'name' => $_SESSION['user']];
        } elseif (isset($_COOKIE['email'], $_COOKIE['user'])) {
            $data['user'] = ['email' => $_COOKIE['email'], 'name' => $_COOKIE['user']];
        }

        $body = $this->view->render('login', $data);

        return new HTMLResponse(['200 OK'], $body);
    }

    public function loginPost($params): IResponse
    {
        $data = [];
        $headers = ['200 OK'];

        try {
            // If there will be any errors, the exception will be thrown. Thus, the code below will not be executed.
            $model = (new AuthenticateService($this->model))->run($params['email'], $params['password']);

            if (isset($params['remember_me'])) {
                setcookie('email', $params['email'], time() + (86400 * 7), '/');
                setcookie('user', $model->user->first_name, time() + (86400 * 7), '/');
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

        if (isset($_COOKIE['email'])) {
            setcookie('email', '', time() - (86400 * 7), '/');
            setcookie('user', '', time() - (86400 * 7), '/');
        }

        return new HTMLResponse(['Location:/login'], $this->view->render('login', []));
    }

    public function register($params): IResponse
    {
        $data = [];

        if ((isset($_SESSION['user'], $_SESSION['email']))) {
            $data['user'] = ['email' => $_SESSION['email'], 'name' => $_SESSION['user']];
        } elseif (isset($_COOKIE['email'], $_COOKIE['user'])) {
            $data['user'] = ['email' => $_COOKIE['email'], 'name' => $_COOKIE['user']];
        }

        $body = $this->view->render('register', $data);

        return new HTMLResponse(['200 OK'], $body);
    }

    public function registerPOST($params): IResponse
    {
        $data = [];
        $headers = ['200 OK'];
        $serviceParams = array_intersect_key($params, array_flip(['email', 'first_name', 'last_name', 'password', 'password_confirmation']));

        try {
            // If there will be any errors, the exception will be thrown. Thus, the code below will not be executed.
            $model = (new RegistrationService($this->model))->run(...$serviceParams);

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