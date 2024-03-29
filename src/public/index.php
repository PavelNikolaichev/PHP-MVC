<?php

use App\controllers\LoginController;
use App\controllers\UserController;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;
use App\Core\Router;
use App\Core\ServiceProvider;
use App\core\Services\CatalogService;
use App\core\SessionManager;

require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$serviceProvider = new ServiceProvider();
$pdo = $serviceProvider->make('ConnectDb');
$router = $serviceProvider->make(Router::class);
$serviceProvider->make(SessionManager::class);

$router->get('/', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(UserController::class)->list($params);
});

$router->get('/login', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(LoginController::class)->login($params);
});
$router->post('/login', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(LoginController::class)->loginPost($params);
});
$router->post('/logout', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(LoginController::class)->logoutPost($params);
});

$router->get('/register', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(LoginController::class)->register($params);
});
$router->post('/register', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(LoginController::class)->registerPOST($params);
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

$router->get('/file-upload', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(\App\controllers\FileUploadController::class)->index($params);
});

$router->post('/file-upload', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(\App\controllers\FileUploadController::class)->upload($params);
});

$router->get('/catalog', function ($params) use ($serviceProvider) {
    return $serviceProvider->make(CatalogService::class)->run($params);
});

$router->addNotFoundRoute(function () {
    return (new HTMLResponse(['404 Not Found'], '<p>404 Not Found</p>'));
});

/* @var IResponse $response */
$response = $router->run();
header(implode($response->getHeaders()));
echo $response->getBody();
