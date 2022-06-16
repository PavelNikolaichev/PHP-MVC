<?php

namespace App\Core;

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
    public function actionIndex(): void
    {
        $this->view->render('index');
    }
}