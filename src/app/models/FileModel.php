<?php

namespace App\models;

use App\Core\Model;

class FileModel extends Model
{
    public string $name;
    public string $extension;
    public string $meta;
    public string $size;

    public function __construct(string $name, string $extension, string $meta, int $size)
    {
        $this->name = $name;
        $this->extension = $extension;
        $this->meta = $meta;
        $this->size = $size;
    }

    public function isAllowed(): bool
    {
        $allowedTypes = ['txt', 'jpg', 'jpeg', 'png'];

        return in_array($this->extension, $allowedTypes);
    }

    /**
     * @return array
     */
    public function toReadable(): array
    {
        return [
            'name' => $this->name,
            'extension' => $this->extension,
            'meta' => $this->meta,
            'size' => $this->readableSize(),
        ];
    }

    public function readableSize(): string
    {
        return number_format($this->size / 1048576, 2) . ' MB';
    }

    public static function createFromFile(mixed $file): FileModel
    {
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);

        if ($fileExt !== 'txt') {
            $fileMeta = getimagesize($file['tmp_name']);
        }

        $fileMeta = isset($fileMeta) ? $fileMeta[3] : '';
        $fileSize = $file['size'];

        return new FileModel($file['tmp_name'], $fileExt, $fileMeta, $fileSize);
    }
}