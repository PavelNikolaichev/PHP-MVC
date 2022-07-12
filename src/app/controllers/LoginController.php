<?php

namespace App\controllers;

use App\Core\Controller;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;
use App\models\LoginModel;

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

        $model = new LoginModel($params['email'], $params['username'], $params['password']);

        $data['errors'] = $model->validate();

        if (!empty($data['errors'])) {
            return new HTMLResponse(['400 Bad Request'], $this->view->render('login', $data));
        }

        $foundedUser = $this->model->fetch($model->email);

        if ($foundedUser !== null) {
            if ($foundedUser->name === $model->name && password_verify($model->password, $foundedUser->password)) {
                $_SESSION['email'] = $model->email;
                $_SESSION['user'] = $model->name;
                $data['user'] = ['email' => $model->email, 'name' => $model->name];
            } else {
                $data['errors']['password'] = 'Wrong password.';
            }
        }

        $body = $this->view->render('login', $data);

        return new HTMLResponse([(empty($data['errors'])) ? '200 OK' : '400 Bad Request'], $body);
    }

    public function logoutPost($params): IResponse
    {
        if (isset($_SESSION['user'])) {
            session_destroy();
        }

        return new HTMLResponse(['200 OK Location:/login'], $this->view->render('login', []));
    }
}