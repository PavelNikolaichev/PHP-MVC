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
    public function createOrUpdate(array $params = []): IResponse
    {
        $data = $this->sendUser($params);


        return new JSONResponse(['200 OK'], $data);
    }

    public function user(array $params = []): IResponse
    {
        $data = [];

        $data['user'] = $this->model->fetch($params['id']);
        $body = $this->view->render('user', $data);

        return new HTMLResponse(['200 OK'], $body);
    }

    public function delete(array $params = []): IResponse
    {
        $data = [];

        $this->model->delete($params['id']);
        $data['deleted_id'] = $params['id'];

        return new JSONResponse(['200 OK'], $data);
    }

    public function list(array $params = []): IResponse
    {
        $data = [];

        $data['users'] = $this->model->fetchAll();

        $body = $this->view->render('userList', $data);

        return new HTMLResponse(['200 OK'], $body);
    }

    public function getList(array $params = []): IResponse
    {
        $data = [];

        $data['users'] = $this->model->fetchAll();

        return new JSONResponse(['200 OK'], $data);
    }

    /**
     * @param array $params
     * @return array
     */
    private function sendUser(array $params): array
    {
        $model = new UserModel(...$params);
        $data['errors'] = $model->validate();

        if (empty($data['errors'])) {
            $data['user'] = $this->model->save($model);
        }

        return $data;
    }
}
