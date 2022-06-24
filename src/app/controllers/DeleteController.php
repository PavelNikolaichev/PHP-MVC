<?php

namespace App\Controllers;

use App\Core\Controller;
use App\core\Responses\IResponse;
use App\core\Responses\JSONResponse;

class DeleteController extends Controller
{
    /**
     * Method to pass the data to the view.
     *
     * @param array $params
     *
     * @return IResponse
     */
    final public function actionIndex(array $params = []): IResponse
    {
        $data = [];

        $this->model->delete($params['id']);
        $this->model->fetchAll();

        $data['users'] = $this->model->fetchAll();

        return new JSONResponse(['200 OK'], $data);
    }
}
