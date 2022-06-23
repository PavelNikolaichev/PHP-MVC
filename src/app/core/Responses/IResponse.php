<?php

namespace App\core\Responses;

interface IResponse
{
    public function getBody(): string;
    public function getHeaders(): array;
}
