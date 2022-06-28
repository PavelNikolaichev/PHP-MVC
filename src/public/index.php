<?php

use App\controllers\UserController;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;
use App\Core\Router;
use App\Core\ServiceProvider;

require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$serviceProvider = new ServiceProvider();
$pdo = $serviceProvider->make('ConnectDb');
$router = $serviceProvider->make(Router::class);

$router->get('/', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(UserController::class)->list($params);
});

$router->get('/user', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(UserController::class)->user($params);
});
$router->post('/user', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(UserController::class)->createOrUpdate($params);
});

$router->get('/user-list', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(UserController::class)->list($params);
});
$router->post('/user-list', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(UserController::class)->createOrUpdate($params);
});

$router->post('/delete', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(UserController::class)->delete($params);
});

$router->addNotFoundRoute(function () {
    return (new HTMLResponse(['404 Not Found'], '<p>404 Not Found</p>'));
});

/* @var IResponse $response */
$response = $router->run();
header(implode($response->getHeaders()));
echo $response->getBody();
