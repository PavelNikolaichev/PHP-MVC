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

        if (!isset($params['email'], $params['username'], $params['password'])) {
            $data['error'] = 'Please fill in all fields';
            $body = $this->view->render('login', $data);

            return new HTMLResponse(['200 OK'], $body);
        }

        $model = new LoginModel($params['email'], $params['username'], $params['password']);

        $data['errors'] = $model->validate();

        if (!empty($data['errors'])) {
            return new HTMLResponse(['400 Bad Request'], $this->view->render('login', $data));
        }

        $foundedUser = $this->model->fetch($model->email);

        if ($foundedUser === null) {
            $data['error'] = 'User not found';
            $body = $this->view->render('login', $data);

            return new HTMLResponse(['400 Bad Request'], $body);
        }

        if (!($foundedUser->name === $model->name && password_verify($model->password, $foundedUser->password))) {
            $data['error'] = 'Wrong login credentials';
            $body = $this->view->render('login', $data);

            return new HTMLResponse(['400 Bad Request'], $body);
        }

        $_SESSION['email'] = $model->email;
        $_SESSION['user'] = $model->name;
        $data['user'] = ['email' => $model->email, 'name' => $model->name];

        $body = $this->view->render('login', $data);

        return new HTMLResponse(['200 OK'], $body);
    }

    public function logoutPost($params): IResponse
    {
        if (isset($_SESSION['user'])) {
            session_destroy();
        }

        return new HTMLResponse(['Location:/login'], $this->view->render('login', []));
    }
}