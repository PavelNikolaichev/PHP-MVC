<?php

namespace App\Core;

use App\core\Responses\IResponse;

class Router
{
    private array $routes = [];
    private $notFoundRoute;
    private const METHOD_POST = 'POST';
    private const METHOD_GET = 'GET';

    /**
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function get(string $path, callable $callback): void
    {
        $this->addRoute(self::METHOD_GET, $path, $callback);
    }

    /**
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function post(string $path, callable $callback): void
    {
        $this->addRoute(self::METHOD_POST, $path, $callback);
    }

    /**
     * @param callable $callback
     * @return void
     */
    public function addNotFoundRoute(callable $callback): void
    {
        $this->notFoundRoute = $callback;
    }

    /**
     * @param string $method
     * @param string $path
     * @param callable $callback
     * @return void
     */
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

        $index = $method. ' ' .$requestPath;
        $callback = null;

        if (isset($this->routes[$index])) {
            $callback = $this->routes[$index]['callback'];
        } elseif (isset($this->notFoundRoute)) {
            $callback = $this->notFoundRoute;
        }

        return $callback(array_merge($_GET, $_POST));
    }
}