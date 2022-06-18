<?php

namespace App\Core;

use app\Controllers\UserController;
use app\Controllers\UserListController;
use App\core\Database\Database;
use app\Models\UserModel;

class ServiceProvider
{
    private array $map = [];
    private array $singletons = [];
    private array $instances = [];

    /**
     *  Constructor for ServiceProvider.
     */
    public function __construct()
    {
        $this->bootstrap();
    }

    private function bootstrap(): void
    {
        $this->map = [
            UserController::class => function(string $class, ServiceProvider $serviceProvider) {
                return new $class(
                    $serviceProvider->make(UserModel::class),
                    $serviceProvider->make(View::class)
                );
            },
            UserListController::class => function(string $class, ServiceProvider $serviceProvider) {
                return new $class(
                    $serviceProvider->make(UserModel::class),
                    $serviceProvider->make(View::class)
                );
            },
            'ConnectDb' => function() {
                return new Database();
            },
        ];

        $this->singletons = [
            'ConnectDb',
        ];
    }

    /**
     * Method to make an instance of a class.
     * @param string $class - name of the class.
     * @return mixed - instance of the class.
     */
    final public function make(string $class): mixed
    {
        if (in_array($class, $this->singletons, true)) {
            if (isset($this->instances[$class])) {
                return $this->instances[$class];
            }

            $this->instances[$class] = isset($this->map[$class])
                ? call_user_func($this->map[$class])
                : new $class;

            return $this->instances[$class];
        }

        return isset($this->map[$class])
            ? call_user_func($this->map[$class])
            : new $class;
    }

    /**
     * Method to set a map of classes.
     * @param array $map - map of classes. Should look like: [className => callable function to create].
     */
    final public function setMap(array $map): void
    {
        $this->map = $map;
    }

    /**
     * Method to change a parameter of the map.
     * @param string $className - name of the class.
     * @param callable $func - function to be called when the class is instantiated.
     * @return $this
     */
    final public function setClass(string $className, callable $func): ServiceProvider
    {
        $this->map[$className] = $func;

        return $this;
    }

    /**
     * Method to add a class to the singletons array.
     * @param string $className - name of the class.
     * @return $this
     */
    final public function addSingleton(string $className): ServiceProvider
    {
        $this->singletons[] = $className;

        return $this;
    }


}