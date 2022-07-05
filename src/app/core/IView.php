<?php

namespace App\core;

interface IView
{
    public function render(string $name, array $data): string;
}