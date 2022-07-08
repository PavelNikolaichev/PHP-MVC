<?php

namespace App\core\Log;

use Psr\Log\AbstractLogger;

class FileLogger extends AbstractLogger
{
    public function log($level, $message, array $context = []): void
    {
        $logFile = $this->chooseLogFile();

        $message = $this->interpolate($message, $context);
        $message = '[' . date('Y-m-d H:i:s') . '] [' . $level . '] ' . $message . PHP_EOL;

        file_put_contents($logFile, $message, FILE_APPEND);
    }

    private function chooseLogFile(): string
    {
        return __DIR__ . $_ENV['LOG_PATH'] . 'upload_' . date('dmY') .'log';
    }

    private function interpolate($message, array $context = array()): string
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be cast to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}