<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private $notFoundRoute;
    private const METHOD_POST = 'POST';
    private const METHOD_GET = 'GET';

    public function get(string $path, callable $callback): void
    {
        $this->addRoute(self::METHOD_GET, $path, $callback);
    }

    public function post(string $path, callable $callback): void
    {
        $this->addRoute(self::METHOD_POST, $path, $callback);
    }

    public function addNotFoundRoute(callable $callback): void
    {
        $this->notFoundRoute = $callback;
    }

    private function addRoute(string $method, string $path, callable $callback): void
    {
        $this->routes[$method . ' ' . $path] = [
            'path' => $path,
            'method' => $method,
            'callback' => $callback,
        ];
    }

    /**
     * @return IResponse
     */
    public function run(): IResponse
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $method = $_SERVER['REQUEST_METHOD'];
        $callback = null;

        foreach ($this->routes as $route) {
            if (($route['path'] === $requestPath) && $method === $route['method']) {
                $callback = $route['callback'];
            }
        }

        if (!$callback) {
            header('HTTP/1.1 404 Not Found');
            if (!empty($this->notFoundRoute)) {
                $callback = $this->notFoundRoute;
            }
        }

        $callback(array_merge($_GET, $_POST));
    }
}