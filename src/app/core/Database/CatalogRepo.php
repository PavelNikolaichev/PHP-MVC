<?php

namespace App\core\Database;

use App\models\CatalogUnitModel;
use App\models\CategoryModel;
use DateTime;

class CatalogRepo implements ICatalogRepo
{
    private array $products;
    private array $services;

    public function __construct()
    {
        $this->products = [
            new CatalogUnitModel(
                'Laptop',
                100,
                1,
                new DateTime('2020-01-01'),
                new DateTime('2020-01-01'),
                [
                    new CategoryModel('Manufacturer', 'Lenovo', 1),
//                    new CategoryModel('Release Date', new DateTime('now'), 2),
                    new CategoryModel('Model', 'ThinkPad X1 Carbon', 3),
                ]
            ),
            new CatalogUnitModel(
                'Fridge',
                100,
                2,
                new DateTime('2020-01-01'),
                new DateTime('2020-01-01'),
                [
                    new CategoryModel('Manufacturer', 'Panasonic', 1),
//                    new CategoryModel('Release Date', new DateTime('now'), 2),
                    new CategoryModel('Model', 'KX-TZS', 3),
                ]
            ),
            new CatalogUnitModel(
                'TV Set',
                100,
                3,
                new DateTime('2020-01-01'),
                new DateTime('2020-01-01'),
                [
                    new CategoryModel('Manufacturer', 'Samsung', 1),
//                    new CategoryModel('Release Date', new DateTime('now'), 2),
                    new CategoryModel('Model', 'UE40J5100', 3),
                ]
            ),
        ];
        $this->services = [
            new CatalogServiceModel(
                'Warranty Service',
                100,
                4,
                new DateTime('2020-01-01'),
                new DateTime('2020-01-01'),
                'Laptop',
                [
//                    new CategoryModel('Deadline', new DateTime('now'), 2),
                ]
            )
        ];
    }

    public function fetchAll(): array
    {
        return array_merge($this->products, $this->services);
    }

    public function fetchRelatedServices(string $relType): array
    {
        $catalog = [];

        foreach ($this->services as $unit) {
            if ($unit->getRelType() === $relType) {
                $catalog[] = $unit;
            }
        }

        return $catalog;
    }
}