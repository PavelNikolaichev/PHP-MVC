<?php

use App\core\Database\MigrationsHandler;
use App\Core\ServiceProvider;

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$serviceProvider = new ServiceProvider();
$pdo = $serviceProvider->make('ConnectDb')->getPDO();
$migrationsHandler = new MigrationsHandler($pdo);
$migrationsHandler->applyMigrations();