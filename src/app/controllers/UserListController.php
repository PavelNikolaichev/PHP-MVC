<?php

namespace App\Controllers;

use App\Core\Controller;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;

class UserListController extends Controller
{
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