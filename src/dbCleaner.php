<?php

use App\core\Database\DBCleaner;
use App\Core\ServiceProvider;

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$serviceProvider = new ServiceProvider();
$pdo = $serviceProvider->make('ConnectDb')->getPDO();

$dbCleaner = new DBCleaner($pdo);
$dbCleaner->clean();