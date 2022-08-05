<?php

namespace App\core\Database;

use App\models\AttributeModel;
use App\models\CatalogUnitModel;
use DateTime;

class CatalogRepository implements ICatalogRepository
{
    private array $items;

    public function __construct()
    {
        $this->items = [
            new CatalogUnitModel(
                'Product',
                100,
                1,
                [
                    (new AttributeModel('ProductType', 'Laptop', 0))->getName()
                    => (new AttributeModel('ProductType', 'Laptop', 0))->getValue(),
                    (new AttributeModel('Manufacturer', 'Lenovo', 1))->getName()
                    => (new AttributeModel('Manufacturer', 'Lenovo', 1))->getValue(),
                    (new AttributeModel('Release Date', new DateTime('now'), 2))->getName()
                    => (new AttributeModel('Release Date', new DateTime('now'), 2))->getValue(),
                    (new AttributeModel('Model', 'ThinkPad X1 Carbon', 3))->getName()
                    => (new AttributeModel('Model', 'ThinkPad X1 Carbon', 3))->getValue(),
                ]
            ),
            new CatalogUnitModel(
                'Product',
                100,
                2,
                [
                    (new AttributeModel('ProductType', 'Fridge', 0))->getName()
                    => (new AttributeModel('ProductType', 'Fridge', 0))->getValue(),
                    (new AttributeModel('Manufacturer', 'Panasonic', 1))->getName()
                    => (new AttributeModel('Manufacturer', 'Panasonic', 1))->getValue(),
                    (new AttributeModel('Release Date', new DateTime('now'), 2))->getName()
                    => (new AttributeModel('Release Date', new DateTime('now'), 2))->getValue(),
                    (new AttributeModel('Model', 'KX-TZS', 3))->getName()
                    => (new AttributeModel('Model', 'KX-TZS', 3))->getValue(),
                ]
            ),
            new CatalogUnitModel(
                'Product',
                100,
                3,
                [
                    (new AttributeModel('ProductType', 'TV Set', 0))->getName()
                    =>(new AttributeModel('ProductType', 'TV Set', 0))->getValue(),
                    (new AttributeModel('Manufacturer', 'Samsung', 1))->getName()
                    =>(new AttributeModel('Manufacturer', 'Samsung', 1))->getValue(),
                    (new AttributeModel('Release Date', new DateTime('now'), 2))->getName()
                    =>(new AttributeModel('Release Date', new DateTime('now'), 2))->getValue(),
                    (new AttributeModel('Model', 'UE40J5100', 3))->getName()
                    =>(new AttributeModel('Model', 'UE40J5100', 3))->getValue(),
                ]
            ),
            new CatalogUnitModel(
                'Product',
                100,
                5,
                [
                    (new AttributeModel('ProductType', 'Mobile Phone', 0))->getName()
                    =>(new AttributeModel('ProductType', 'Mobile Phone', 0))->getValue(),
                    (new AttributeModel('Manufacturer', 'Samsung', 1))->getName()
                    =>(new AttributeModel('Manufacturer', 'Samsung', 1))->getValue(),
                    (new AttributeModel('Release Date', new DateTime('now'), 2))->getName()
                    =>(new AttributeModel('Release Date', new DateTime('now'), 2))->getValue(),
                    (new AttributeModel('Model', 'Galaxy S8', 3))->getName()
                    =>(new AttributeModel('Model', 'Galaxy S8', 3))->getValue(),
                ]
            ),
            new CatalogUnitModel(
                'Service',
                100,
                4,
                [
                    (new AttributeModel('ServiceType', 'Warranty Service', 0))->getName()
                    =>(new AttributeModel('ServiceType', 'Warranty Service', 0))->getValue(),
                    (new AttributeModel('RelationTypes', ['Laptop', 'TV Set'], 2))->getName()
                    =>(new AttributeModel('RelationTypes', ['Laptop', 'TV Set'], 2))->getValue(),
                    (new AttributeModel('Deadline', new DateTime('2020-01-01'), 3))->getName()
                    =>(new AttributeModel('Deadline', new DateTime('2020-01-01'), 3))->getValue(),
                ]
            ),
            new CatalogUnitModel(
                'Service',
                100,
                4,
                [
                    (new AttributeModel('ServiceType', 'Delivery Service', 0))->getName()
                    =>(new AttributeModel('ServiceType', 'Delivery Service', 0))->getValue(),
                    (new AttributeModel('RelationTypes', ['Fridge', 'TV Set'], 2))->getName()
                    =>(new AttributeModel('RelationTypes', ['Fridge', 'TV Set'], 2))->getValue(),
                    (new AttributeModel('Deadline', new DateTime('2020-01-01'), 3))->getName()
                    =>(new AttributeModel('Deadline', new DateTime('2020-01-01'), 3))->getValue(),
                ]
            ),
            new CatalogUnitModel(
                'Service',
                100,
                4,
                [
                    (new AttributeModel('ServiceType', 'Install and Configure Service', 0))->getName()
                    =>(new AttributeModel('ServiceType', 'Install and Configure Service', 0))->getValue(),
                    (new AttributeModel('Manufacturer', 'Lenovo', 1))->getName()
                    =>(new AttributeModel('Manufacturer', 'Lenovo', 1))->getValue(),
                    (new AttributeModel('RelationTypes', ['Laptop', 'TV Set', 'Mobile Phone'], 2))->getName()
                    =>(new AttributeModel('RelationTypes', ['Laptop', 'TV Set', 'Mobile Phone'], 2))->getValue(),
                    (new AttributeModel('Deadline', new DateTime('2020-01-01'), 3))->getName()
                    =>(new AttributeModel('Deadline', new DateTime('2020-01-01'), 3))->getValue(),
                ]
            ),
        ];
    }

    public function fetchAll(): array
    {
        return $this->items;
    }

    public function fetchRelatedServices(string $relType): array
    {
        $catalog = [];

        foreach ($this->items as $unit) {
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