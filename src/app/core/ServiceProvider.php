<?php

namespace App\Core;

use App\controllers\FileUploadController;
use App\controllers\LoginController;
use App\controllers\UserController;
use App\core\Database\AttemptsRepository;
use App\core\Database\CatalogRepository;
use App\Core\Database\Database;
use App\core\Database\FileRepository;
use App\core\Database\IAttemptsRepository;
use App\core\Database\ICatalogRepository;
use App\core\Database\IRepository;
use App\core\Database\LoginRepository;
use App\Core\Database\RESTRepository;
use App\core\Log\FileLogger;
use App\core\Log\LoginLogger;
use App\core\Services\AuthenticateService;
use App\core\Services\RegistrationService;
use App\core\Services\CatalogService;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionException;

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

    /**
     * Bootstraps the application.
     *
     * @return void
     * @noinspection PhpUnusedParameterInspection
     */
    private function bootstrap(): void
    {
        $this->map = [
            UserController::class => function (string $class, ServiceProvider $serviceProvider) {
                return new $class(
                    $serviceProvider->make(IRepository::class),
                    $serviceProvider->make(IView::class)
                );
            },
            QueryBuilder::class => function (string $class, ServiceProvider $serviceProvider) {
                return new $class(
                    $serviceProvider->make('ConnectDb')->getPDO()
                );
            },
            IRepository::class => function (string $class, ServiceProvider $serviceProvider) {
                return new RESTRepository(
                    $serviceProvider->make(CurlManager::class)
                );
            },
            FileUploadController::class => function (string $class, ServiceProvider $serviceProvider) {
                return new $class(
                    $serviceProvider->make(FileRepository::class),
                    $serviceProvider->make(IView::class),
                    $serviceProvider->make(IAuthenticatedUser::class)
                );
            },
            LoginController::class => function (string $class, ServiceProvider $serviceProvider) {
                return new $class(
                    $serviceProvider->make(LoginRepository::class),
                    $serviceProvider->make(IView::class),
                    $serviceProvider->make(AuthenticateService::class),
                    $serviceProvider->make(RegistrationService::class),
                    $serviceProvider->make(IAuthenticatedUser::class)
                );
            },
            FileRepository::class => function (string $class, ServiceProvider $serviceProvider) {
                return new $class(
                    $serviceProvider->make(FileLogger::class)
                );
            },
            IView::class => function (string $class, ServiceProvider $serviceProvider) {
                return new TwigView();
            },
            LoggerInterface::class => function (string $class, ServiceProvider $serviceProvider) {
                return new FileLogger();
            },
            IAuthenticatedUser::class => function (string $class, ServiceProvider $serviceProvider) {
                return $serviceProvider->make(SessionAuthenticatedUser::class);
            },
            IAttemptsRepository::class => function (string $class, ServiceProvider $serviceProvider) {
                return new AttemptsRepository(
                    $serviceProvider->make(QueryBuilder::class),
                    $serviceProvider->make(LoginLogger::class)
                );
            },
            ICatalogRepository::class => function (string $class, ServiceProvider $serviceProvider) {
                return $serviceProvider->make(CatalogRepository::class);
            },
            'ConnectDb' => function () {
                return new Database();
            },
        ];

        $this->singletons = [
            'ConnectDb',
            SessionManager::class,
            IAuthenticatedUser::class,
        ];
    }

    /**
     * Method to make an instance of a class.
     *
     * @param string $class - name of the class.
     *
     * @return mixed - instance of the class.
     * @throws ReflectionException
     */
    final public function make(string $class): mixed
    {
        if (in_array($class, $this->singletons, true)) {
            if (isset($this->instances[$class])) {
                return $this->instances[$class];
            }

            $this->instances[$class] = isset($this->map[$class])
                ? call_user_func($this->map[$class], $class, $this)
                : $this->makeThroughReflection($class);

            return $this->instances[$class];
        }

        return isset($this->map[$class])
            ? call_user_func($this->map[$class], $class, $this)
            : $this->makeThroughReflection($class);
    }

    /**
     * Method to make an instance of a class through reflection.
     * @param string $class - name of the class.
     * @return object|null - instance of the class.
     * @throws ReflectionException - if class does not exist.
     */
    final public function makeThroughReflection(string $class): ?object
    {
        $reflection = new ReflectionClass($class);

        if ($constructor = $reflection->getConstructor()) {
            $parameters = $constructor->getParameters();
            $dependencies = [];

            foreach ($parameters as $parameter) {
                $dependencies[] = $this->make($parameter->getType());
            }

            return $reflection->newInstanceArgs($dependencies);
        }

        return $reflection->newInstanceArgs();
    }

    /**
     * Method to set a map of classes.
     *
     * @param array $map - map of classes. Should look like: [className => callable function to create].
     */
    final public function setMap(array $map): void
    {
        $this->map = $map;
    }

    /**
     * Method to change a parameter of the map.
     *
     * @param string   $className - name of the class.
     * @param callable $func      - function to be called when the class is instantiated.
     *
     * @return $this
     */
    final public function setClass(string $className, callable $func): ServiceProvider
    {
        $this->map[$className] = $func;

        return $this;
    }

    /**
     * Method to add a class to the singletons array.
     *
     * @param string $className - name of the class.
     *
     * @return $this
     */
    final public function addSingleton(string $className): ServiceProvider
    {
        $this->singletons[] = $className;

        return $this;
    }
}
