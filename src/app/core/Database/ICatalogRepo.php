<?php

namespace App\core\Database;

interface ICatalogRepo
{
    public function fetchAll() : array;
    public function fetchRelatedServices(string $relType) : array;
}