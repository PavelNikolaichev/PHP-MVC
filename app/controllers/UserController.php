<?php

namespace app\Controllers;

use App\Models\UserModel;
use App\Core\Controller;
use App\Core\View;

class UserController extends Controller
{
    /**
     * Constructor for UserController.
     */
    final public function __construct()
    {
        $this->model = new UserModel();
        $this->view = new View();
    }

    /**
     * Method to pass the data to the view.
     * @return void
     */
    final public function actionIndex(): void
    {
        $data = $this->model->fetch();
        $this->view->render('user', $data);
    }
}