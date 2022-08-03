<?php

namespace App\core\Database;

class CatalogServiceModel
{

    /**
     * @param string $type
     * @param int $price
     * @param int $id
     * @param \DateTime $created_at
     * @param \DateTime $modified_at
     * @param array $categories
     */
    public function __construct(
        private string $type,
        private int $price,
        private int $id,
        private \DateTime $created_at,
        private \DateTime $modified_at,
        private string $relType,
        private array $categories
    ) {}

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return \DateTime
     */
    public function getModifiedAt(): \DateTime
    {
        return $this->modified_at;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getRelType(): string
    {
        return $this->relType;
    }
}