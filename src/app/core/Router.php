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
     * Method to add a route on GET-method.
     *
     * @param string   $path     - path to the route.
     * @param callable $callback - callback to be executed when the route is matched.
     *
     * @return void
     */
    final public function get(string $path, callable $callback): void
    {
        $this->addRoute(self::METHOD_GET, $path, $callback);
    }

    /**
     * Method to add a route on POST-method.
     *
     * @param string   $path     - path to the route.
     * @param callable $callback - callback to be executed when the route is matched.
     *
     * @return void
     */
    final public function post(string $path, callable $callback): void
    {
        $this->addRoute(self::METHOD_POST, $path, $callback);
    }

    /**
     * Method to add a route on 404 response.
     *
     * @param callable $callback - callback to be executed when the route is matched.
     *
     * @return void
     */
    final public function addNotFoundRoute(callable $callback): void
    {
        $this->notFoundRoute = $callback;
    }

    /**
     * Method to add the route.
     *
     * @param string   $method   - method of the route.
     * @param string   $path     - path to the route.
     * @param callable $callback - callback to be executed when the route is matched.
     *
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
     * Method to match the route.
     *
     * @return IResponse - response to be returned.
     */
    final public function run(): IResponse
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
