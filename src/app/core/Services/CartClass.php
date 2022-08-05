<?php

namespace App\core\Services;

use App\models\CatalogUnitModel;
use RuntimeException;

class CartClass
{
    private array $relations = [];

    public function __construct(private array $items=[]) {}

    public function add(CatalogUnitModel $item, int $relatedID=null): static
    {
        if ($item->type === 'Service') {
            if ($relatedID === null) {
                throw new RuntimeException('Services cannot be added without a related product');
            }

            $this->relations[$relatedID][] = $item->id;
        }

        $this->items[] = $item;
        return $this;
    }

    public function remove(mixed $id): static
    {
        unset($this->items[$id]);

        if (isset($this->relations[$id])) {
            $this->items = array_filter($this->items, function($i) use ($id) {
                return !in_array($i->id, $this->relations[$id], true);
            });

            unset($this->relations[$id]);
        }

        $this->items = array_values($this->items);
        return $this;
//        $this->items = array_filter($this->items, static function($i) use ($item) {
//            return $i !== $item;
//        });
//
//        if (isset($this->relations[$item->id])) {
//            $this->items = array_filter($this->items, function($i) use ($item) {
//                return !in_array($i->id, $this->relations[$item->id], true);
//            });
//            unset($this->relations[$item->id]);
//
//            // Reindex array after removing the element
//            $this->relations = array_values($this->relations);
//        }
//
//        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function calculatePrice(): int
    {
        return array_sum(array_map(function($item) {
            return $item->price;
        }, $this->items));
    }
}