<?php

namespace App\controllers;

use App\Core\Controller;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;
use App\core\Services\AuthenticateService;
use App\models\LoginModel;
use Exception;

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
            // If there will be any errors, the exception will be thrown. Thus, the code below will not be executed.
            $model = (new AuthenticateService($this->model))->run($params['email'], $params['password']);

            $data['user'] = ['email' => $model->user->email, 'name' => $model->user->name];

            $_SESSION['email'] = $model->user->email;
            $_SESSION['user'] = $model->user->name;
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

    public function register($params): IResponse
    {
        $data = [];

        if ((isset($_SESSION['user'], $_SESSION['email']))) {
            $data['user'] = ['email' => $_SESSION['email'], 'name' => $_SESSION['user']];
        }

        $body = $this->view->render('register', $data);

        return new HTMLResponse(['200 OK'], $body);
    }

    public function registerPOST($params): IResponse
    {
        $data = [];
        $headers = ['200 OK'];

        try {
            // If there will be any errors, the exception will be thrown. Thus, the code below will not be executed.
            $model = $this->model->save(new LoginModel($params['email'], $params['username'], $params['password']));

            $data['errors'] = $model->validate();

            if (empty($data['errors'])) {
                $data['user'] = $this->model->save($model);
            }

            $_SESSION['email'] = $model->user->email;
            $_SESSION['user'] = $model->user->name;

            $data['user'] = ['email' => $model->user->email, 'name' => $model->user->name];
        } catch (Exception $e) {
            $data['errors'][] = $e->getMessage();
            $headers = ['400 Bad Request'];
        }

        $body = $this->view->render('register', $data);
        return new HTMLResponse($headers, $body);
    }
}