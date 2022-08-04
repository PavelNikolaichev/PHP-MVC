<?php

namespace App\models;

use App\Core\Model;
use DateTime;

class CatalogUnitModel extends Model
{
    private DateTime $created_at;
    private DateTime $modified_at;

    private array $cast = [
        'created_at' => 'datetime',
        'modified_at' => 'datetime',
    ];

    public function __construct(
        private string $type,
        private float $price,
        private int $id,
        private array $attributes
    ) {
        $this->created_at = new DateTime('now');
        $this->modified_at = new DateTime('now');
    }

    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            if (isset($this->cast[$name])) {
                return match ($this->cast[$name]) {
                    'datetime' => $this->$name->format('Y-m-d H:i:s'),
                    default => $this->$name,
                };
            }

            return $this->$name;
        }

        $attrs = $this->getAttributesDict();

        if (isset($attrs[$name])) {
            return $attrs[$name];
        }
    }

    public function __set(string $name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    public function __isset(string $name): bool
    {
        return property_exists($this, $name);
    }

    public function getAttributesDict(): array
    {
        return array_reduce($this->attributes, function($dict, $attr) {
            $dict[$attr->getName()] = $attr->getValue();
            return $dict;
        }, []);
    }
}