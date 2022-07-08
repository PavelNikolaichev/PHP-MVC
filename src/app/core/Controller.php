<?php

namespace App\Core;

use App\core\Database\IRepository;
use App\core\Responses\IResponse;

class Controller
{
    /**
     * @var Model - Model object.
     * @var View  - View object.
     */
    public IRepository $model;
    public IView $view;

    /**
     * Constructor for Controller.
     */
    public function __construct(IRepository $model, IView $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    /**
     * Method to pass the data to the view.
     *
     * @return IResponse
     */
    public function actionIndex(): IResponse
    {
//        $this->view->render('index');
//        return new IResponse(['200 OK'], ['index']);
    }
}
