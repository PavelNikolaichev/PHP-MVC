<?php

namespace app\Controllers;

use App\Core\Controller;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;
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
     * @param array $params
     * @return IResponse
     */
    final public function actionIndex(array $params = []): IResponse
    {
        $data = $this->model->fetchAll();

        $body = $this->view->render('userList', $data);

        return new HTMLResponse(['200 OK'], $body);
    }
}