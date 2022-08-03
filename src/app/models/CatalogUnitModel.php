<?php

namespace App\models;

use App\Core\Model;
use DateTime;

class CatalogUnitModel extends Model
{
    public function __construct(
        private string $type,
        private float $price,
        private int $id,
        private DateTime $added_at,
        private DateTime $modified_at,
        private array $categories
    ) {}

    public function getType(): string
    {
        return $this->type;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAddedAt(): DateTime
    {
        return $this->added_at;
    }

    public function getModifiedAt(): DateTime
    {
        return $this->modified_at;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }
}