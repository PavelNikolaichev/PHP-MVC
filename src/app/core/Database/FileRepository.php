<?php

namespace App\core\Database;

use App\core\Model;
use App\models\FileModel;
use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;

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

        if (file_exists(__DIR__  . $_ENV['UPLOAD_PATH'])) {
            $files = scandir(__DIR__ . $_ENV['UPLOAD_PATH']);
            $files = array_slice($files, 2);
        }

        foreach ($files as $file) {
            $files_exif[] = $this->getFileData($file);
        }

        return $files_exif;
    }

    private function createDir(string $dirName): void
    {
        if (!file_exists($dirName)) {
            mkdir($dirName, 0777, true);
        }
    }

    /**
     * @param string $file
     * @return FileModel
     */
    public function getFileData(string $file): FileModel
    {
        $fileInfo = pathinfo($file);
        $fileExt = $fileInfo['extension'];
        $fileName = $fileInfo['filename'];

        if ($fileExt !== 'txt') {
            $fileMeta = getimagesize(__DIR__ . $_ENV['UPLOAD_PATH'] . $file);
        }

        $fileMeta = isset($fileMeta) ? $fileMeta[3] : '';
        $fileSize = filesize(__DIR__ . $_ENV['UPLOAD_PATH'] . $file);

        return new FileModel($fileName, $fileExt, $fileMeta, $fileSize);
    }

    public function fetch(int $id): FileModel|null
    {
        $files = [];

        if (file_exists(__DIR__ . $_ENV['UPLOAD_PATH'])) {
            $files = scandir(__DIR__ . $_ENV['UPLOAD_PATH']);
            $files = array_slice($files, 2);
        }

        return $this->getFileData($files[$id]);
    }

    public function save(FileModel|Model $model): FileModel|null
    {
        $this->logger->info('Uploading file [{name}][{size}]', [
            'name' => $model->name,
            'size' => $model->readableSize()
        ]);

        try {
            if (!$model->isAllowed()) {
                throw new RuntimeException('File extension is not allowed: ' . $model->extension . '.');
            }

            if (disk_free_space(__DIR__) <= $model->size) {
                throw new RuntimeException("There is no free space on disk");
            }

            $fileName = uniqid('', true) . '.' . $model->extension;

            $this->createDir(__DIR__ . $_ENV['UPLOAD_PATH']);

            $filePath = __DIR__ . $_ENV['UPLOAD_PATH'] . $fileName;

            if (!move_uploaded_file($model->name, $filePath)) {
                throw new RuntimeException("Could not move the file " . $fileName);
            }

            $savedModel = $this->getFileData($fileName);

            $this->logger->info('File uploaded successfully. [{name}][{size}]', [
                'name'=>$savedModel,
                'size'=>$savedModel->readableSize()
            ]);
        } catch (RuntimeException $e) {
            $this->logger->error($e->getMessage(), [$model]);
            return null;
        }

        return $savedModel;
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): void
    {
        throw new Exception('Not implemented yet');
    }
}