<?php

namespace App\Controllers;

use App\Core\Controller;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;
use App\core\Responses\JSONResponse;
use App\Models\UserModel;

class UserController extends Controller
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

        if ($_POST) {
            $model = new UserModel(...$params);
            $data['errors'] = $model->validate();

            if (empty($data['errors'])) {
                $this->model->save($model);
            }

            $data['user'] = $this->model->fetch($params['id']);

            return new JSONResponse(['200 OK'], $data);
        }

        $data['user'] = $this->model->fetch($params['id']);
        $body = $this->view->render('user', $data);

        return new HTMLResponse(['200 OK'], $body);
    }
}
