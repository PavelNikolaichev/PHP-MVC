<?php

namespace App\core\Database;

interface ICatalogRepository
{
    public function fetchAll() : array;
    public function fetchRelatedServices(string $relType) : array;
}