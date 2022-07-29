<?php

namespace App\core\Traits;

use App\Core\Model;
use App\Core\QueryBuilder;
use App\models\LoginModel;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

trait TraitIP
{
    private function getIP(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }
}