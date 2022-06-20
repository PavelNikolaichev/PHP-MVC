<?php

namespace App\Controllers;

use App\Core\Controller;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;

class UserController extends Controller
{
    /**
     * Method to pass the data to the view.
     * @param array $params
     * @return HTMLResponse
     */
    final public function actionIndex(array $params = []): IResponse
    {
        $data = $this->model->fetch($params['id']);

//        Get response body from view
        $body = $this->view->render('user', $data);

        return new HTMLResponse(['200 OK'], $body);
    }
}