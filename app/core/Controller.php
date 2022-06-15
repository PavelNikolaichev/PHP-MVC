<?php

namespace app\Core;

abstract class Controller
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
    abstract public function __construct();

    /**
     * Abstract method to pass the data to the view.
     * @return void
     */
    abstract public function actionIndex(): void;
}