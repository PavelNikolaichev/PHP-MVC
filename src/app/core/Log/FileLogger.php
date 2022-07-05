<?php

namespace App\core\Log;

class FileLogger
{
    private ILogger $logger;

    public function __construct(ILogger $logger=null)
    {
        $this->logger = $logger ?? new Logger();
    }

    public function writeLog()
    {
        throw new \Exception('Not implemented yet');
    }
}