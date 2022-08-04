<?php

namespace App\core\Services;

class CartClass
{
    public function __construct(private array $items=[]) {}

    public function add(mixed $product): static
    {
        $this->items[] = $product;
        return $this;
    }

    public function remove(mixed $product): static
    {
        $this->items = array_filter($this->items, function($item) use ($product) {
            return $item !== $product;
        });

        return $this;
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