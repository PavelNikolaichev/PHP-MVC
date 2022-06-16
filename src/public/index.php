<?php
use App\Controllers\UserController;
use App\Controllers\UserListController;
use App\Core\Router;

require_once '../vendor/autoload.php';

$router = new Router();

$router->get('/', function () {
    (new UserController())->actionIndex();
});
$router->get('/user-list', function () {
    (new UserListController())->actionIndex();
});
$router->addNotFoundRoute(function () {
    echo '404 Not Found';
});

$router->run();
