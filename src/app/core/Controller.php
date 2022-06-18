<?php

namespace App\Core;

use App\core\Responses\IResponse;

class Controller
{
    /**
     * @var Model - Model object.
     * @var View - View object.
     */
    public Model $model;
    public View $view;

    /**
     * Constructor for Controller.
     */
    public function __construct(Model $model, View $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    /**
     * Method to pass the data to the view.
     * @return void
     */
    public function actionIndex(): IResponse
    {
//        $this->view->render('index');
//        return new IResponse(['200 OK'], ['index']);
    }
}