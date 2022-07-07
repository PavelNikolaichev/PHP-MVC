<?php

namespace App\core;

use App\core\Database\IRepository;
use App\models\FileModel;
use RuntimeException;
use Psr\Log\LoggerInterface;

class FileRepository implements IRepository
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function fetchAll(): array
    {
        $files = [];
        $files_exif = [];

        if (file_exists('../uploads/')) {
            $files = scandir('../uploads/');
            $files = array_slice($files, 2);
        }

        foreach ($files as $file) {
            $files_exif[] = $this->getFileData($file);
        }

        return $files_exif;
    }

    private function createIfNotExists(string $fileName): void
    {
        if (!file_exists($fileName)) {
            mkdir($fileName);
        }
    }

    /**
     * @param string $file
     * @return FileModel
     */
    public function getFileData(string $file): FileModel
    {
        $fileName = explode('.', $file);
        $fileExt = strtolower(end($fileName));

        if ($fileExt !== 'txt') {
            $fileMeta = getimagesize('../uploads/' . $file);
        }

        $fileMeta = isset($fileMeta) ? $fileMeta[3] : '';
        $fileSize = filesize('../uploads/' . $file);

        return new FileModel($fileName[0], $fileExt, $fileMeta, $fileSize);
    }

    public function fetch(int $id): FileModel|null
    {
        $files = [];
        $files_exif = [];

        if (file_exists('../uploads/')) {
            $files = scandir('../uploads/');
            $files = array_slice($files, 2);
        }

        return $this->getFileData($files[$id]);
    }

    public function save(FileModel|Model $model): FileModel
    {
        $this->logger->info('Uploading file ' . $model->name);

        try {
            if (!$model->isAllowed()) {
                throw new RuntimeException('File extension is not allowed: ' . $model->extension . '.');
            }

            if (disk_free_space(__DIR__) <= $model->size) {
                throw new RuntimeException("There is no free space on disk");
            }

            $fileName = uniqid() . '.' . $model->extension;

            $this->createIfNotExists('../uploads/');

            $filePath = '../uploads/' . $fileName;

            if (!move_uploaded_file($model->name, $filePath)) {
                throw new RuntimeException("Could not move file " . $fileName);
            }

            $savedModel = $this->getFileData($fileName);

            $this->logger->info('File uploaded successfully.', [$savedModel]);
        } catch (RuntimeException $e) {
            $this->logger->error($e->getMessage(), [$model]);
        }

        return $savedModel;
    }

    public function delete(int $id): void
    {
        throw new \Exception('Not implemented yet');
    }
}