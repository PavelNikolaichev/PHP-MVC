<?php

namespace App\controllers;

use App\Core\Controller;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;
use App\models\LoginModel;
use ArgumentCountError;
use Exception;
use http\Exception\InvalidArgumentException;

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
            $model = $this->model->login($params['email'], $params['username'], $params['password']);

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