<?php

namespace App\models;

use App\Core\Model;

class AttributeModel extends Model
{
    public function __construct(private string $name, private mixed $value, private int $id) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getId(): int
    {
        return $this->id;
    }
}