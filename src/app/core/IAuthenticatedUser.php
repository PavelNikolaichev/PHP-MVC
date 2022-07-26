<?php

namespace App\core;

interface IAuthenticatedUser
{
    public function getUser(): ?array;
}