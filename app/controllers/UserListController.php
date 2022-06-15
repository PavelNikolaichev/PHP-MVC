<?php

namespace app\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\UserModel;

class UserListController extends Controller
{
    /**
     * Constructor for UserListController.
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
        $data = $this->model->fetchAll();
        $this->view->render('userList', $data);
    }
}