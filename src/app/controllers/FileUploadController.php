<?php

namespace App\controllers;

use App\Core\Controller;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;
use App\core\Responses\JSONResponse;

class FileUploadController extends Controller
{
    public function index(array $params = []): IResponse
    {
        $data = [
            'files' => $this->getFiles(),
        ];
        $body = $this->view->render('fileUpload', $data);

        return new HTMLResponse(['200 OK'], $body);
    }

    public function upload(array $params = []): IResponse
    {
        $data = [];

        if (isset($_POST)) {
            $file = $_FILES['file'];

            $fileExt = explode('.', $file['name']);
            $fileExt = strtolower(end($fileExt));

            $allowedTypes = ['txt', 'jpg', 'jpeg', 'png'];

            if (in_array($fileExt, $allowedTypes)) {
                if ($file['error'] === 0) {
                    if (disk_free_space(__DIR__) <= filesize($file['tmp_name'])) {
                        $data['error'] = 'Not enough space on disk';
                    }

                    $fileName = uniqid() . '.' . $fileExt;

                    if (!file_exists('../uploads/')) {
                        mkdir('../uploads/');
                    }

                    $filePath = __DIR__ . '/../../uploads/' . $fileName;

                    if (move_uploaded_file($file['tmp_name'], $filePath)) {
                        $data['file'] = $this->getFileData($fileName);
                    } else {
                        $data['error'] = 'Could not move the file into the uploads.';
                    }
                } else {
                    $data['error'] =  'File upload error.';
                }
            } else {
                $data['error'] =  'File extension is not allowed.';
            }
        }

        return new JSONResponse(['200 OK'], $data);
    }

    private function getFiles(): array
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

    /**
     * @param string $fileName
     * @return array
     */
    private function getFileData(string $fileName): array
    {
        $fileExt = explode('.', $fileName);
        $fileExt = strtolower(end($fileExt));

        $allowedTypes = ['jpg', 'jpeg', 'png'];

        if (in_array($fileExt, $allowedTypes)) {
            $fileMeta = getimagesize('../uploads/' . $fileName);
            $fileMeta = $fileMeta ? $fileMeta[3] : '';
        } else {
            $fileMeta = '';
        }

        return [
            'name' => $fileName,
            'size' => number_format(filesize('../uploads/' . $fileName) / 1048576, 2) . ' MB',
            'meta' => $fileMeta ?? '',
        ];
    }
}