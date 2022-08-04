<?php

namespace App\models;

use App\Core\Model;
use DateTime;

class AttributeModel extends Model
{
    public function __construct(private string $name, private mixed $value, private int $id) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): mixed
    {
        if ($this->value instanceof DateTime) {
            return $this->value->format('Y-m-d H:i:s');
        }

        return $this->value;
    }

    public function getId(): int
    {
        return $this->id;
    }
}