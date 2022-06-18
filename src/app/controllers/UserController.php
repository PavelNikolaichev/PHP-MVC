<?php

namespace app\Controllers;

use App\Core\Controller;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;
use App\Core\View;
use App\Models\UserModel;

class UserController extends Controller
{
    /**
     * Constructor for UserController.
     */
    public function __construct(UserModel $userModel = null, View $view = null)
    {
//        parent::__construct();
        $this->model = $userModel ?? new UserModel();
        $this->view = $view ?? new View();
    }

    /**
     * Method to pass the data to the view.
     * @param array $params
     * @return HTMLResponse
     */
    final public function actionIndex(array $params = []): IResponse
    {
        $data = $this->model->fetch();
//        $this->view->render('user', $data);
//        Get response body from view
        $body = $this->view->render('user', $data);

        return new HTMLResponse(['200 OK'], $body);
    }
}