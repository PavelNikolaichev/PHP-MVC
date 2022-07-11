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
        $body = $this->view->render('login', []);

        return new HTMLResponse(['200 OK'], $body);
    }

    public function loginPost($params): IResponse
    {
        $data = [];

        $email = $params['email'];
        $username = $params['username'];
        $password = $params['password'];

        $model = new LoginModel($email, $username, $password);
        $data['errors'] = $model->validate();

        if (is_array($data['errors'])) {
            return new HTMLResponse(['400 Bad Request'], $this->view->render('login', $data));
        }

        if (in_array($model, $this->model->fetchAll(), true)) {
            $_SESSION['email'] = $email;
            $_SESSION['user'] = $username;
            return new HTMLResponse(['200 OK'], $this->view->render('login', $data));
        }

        $body = $this->view->render('login', $data);

        return new HTMLResponse(['400 Bad Request'], $body);
    }
}