<?php

namespace App\controllers;

use App\Core\Controller;
use App\core\LoginForm;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;
use Exception;
use InvalidArgumentException;

class LoginController extends Controller
{
    public function login($params): IResponse
    {
        $data = [...$params];

        if ((isset($_SESSION['user'], $_SESSION['email']))) {
            $data['user'] = ['email' => $_SESSION['email'], 'name' => $_SESSION['user']];
        }

        $body = $this->view->render('login', $data);

        return new HTMLResponse(['200 OK'], $body);
    }

    public function loginPost($params): IResponse
    {
        $data = [];
        $headers = ['200 OK'];

        try {
            $form = new LoginForm($params['email'], $params['username'], $params['password']);

            // If there will be any errors, the exception will be thrown. Thus, the code below will not be executed.
            $form->validate();

            $model = $form->getModel();

            $user = $this->model->fetch($params['email']);

            if ($user === null || !($user->name === $model->name && password_verify($model->password, $user->password))) {
                throw new InvalidArgumentException('Invalid credentials.');
            }

            $_SESSION['email'] = $model->email;
            $_SESSION['user'] = $model->name;
            $data['user'] = ['email' => $model->email, 'name' => $model->name];
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

        return new HTMLResponse(['Location:/login'], $this->view->render('login', []));
    }
}