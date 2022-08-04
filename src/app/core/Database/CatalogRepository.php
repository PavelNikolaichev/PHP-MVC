<?php

namespace App\core\Database;

use App\models\AttributeModel;
use App\models\CatalogUnitModel;
use DateTime;

class CatalogRepository implements ICatalogRepository
{
    private array $products;

    public function __construct()
    {
        $this->products = [
            new CatalogUnitModel(
                'Product',
                100,
                1,
                [
                    new AttributeModel('ProductType', 'Laptop', 0),
                    new AttributeModel('Manufacturer', 'Lenovo', 1),
                    new AttributeModel('Release Date', new DateTime('now'), 2),
                    new AttributeModel('Model', 'ThinkPad X1 Carbon', 3),
                ]
            ),
            new CatalogUnitModel(
                'Product',
                100,
                2,
                [
                    new AttributeModel('ProductType', 'Fridge', 0),
                    new AttributeModel('Manufacturer', 'Panasonic', 1),
                    new AttributeModel('Release Date', new DateTime('now'), 2),
                    new AttributeModel('Model', 'KX-TZS', 3),
                ]
            ),
            new CatalogUnitModel(
                'Product',
                100,
                3,
                [
                    new AttributeModel('ProductType', 'TV Set', 0),
                    new AttributeModel('Manufacturer', 'Samsung', 1),
                    new AttributeModel('Release Date', new DateTime('now'), 2),
                    new AttributeModel('Model', 'UE40J5100', 3),
                ]
            ),
            new CatalogUnitModel(
                'Product',
                100,
                5,
                [
                    new AttributeModel('ProductType', 'Mobile Phone', 0),
                    new AttributeModel('Manufacturer', 'Samsung', 1),
                    new AttributeModel('Release Date', new DateTime('now'), 2),
                    new AttributeModel('Model', 'Galaxy S8', 3),
                ]
            ),
            new CatalogUnitModel(
                'Service',
                100,
                4,
                [
                    new AttributeModel('ServiceType', 'Warranty Service', 0),
                    new AttributeModel('RelationTypes', ['Laptop', 'TV Set'], 2),
                    new AttributeModel('Deadline', new DateTime('2020-01-01'), 3),
                ]
            ),
            new CatalogUnitModel(
                'Service',
                100,
                4,
                [
                    new AttributeModel('ServiceType', 'Delivery Service', 0),
                    new AttributeModel('RelationTypes', ['Fridge', 'TV Set'], 2),
                    new AttributeModel('Deadline', new DateTime('2020-01-01'), 3),
                ]
            ),
            new CatalogUnitModel(
                'Service',
                100,
                4,
                [
                    new AttributeModel('ServiceType', 'Install and Configure Service', 0),
                    new AttributeModel('Manufacturer', 'Lenovo', 1),
                    new AttributeModel('RelationTypes', ['Laptop', 'TV Set', 'Mobile Phone'], 2),
                    new AttributeModel('Deadline', new DateTime('2020-01-01'), 3),
                ]
            ),
        ];
    }

    public function fetchAll(): array
    {
        return $this->products;
    }

    public function fetchRelatedServices(string $relType): array
    {
        $catalog = [];

        foreach ($this->products as $unit) {
            if ($unit->type === 'Service') {
                $relTypes = $unit->RelationTypes;

                if (in_array($relType, $relTypes, true)) {
                    $catalog[] = $unit;
                }
            }
        }

        return $catalog;
    }
}