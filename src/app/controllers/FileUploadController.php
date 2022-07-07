<?php

namespace App\controllers;

use App\Core\Controller;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;
use App\core\Responses\JSONResponse;
use App\models\FileModel;

class FileUploadController extends Controller
{
    public function index(array $params = []): IResponse
    {
        $files = [];

        foreach ($this->model->fetchAll() as $file) {
            $files[] = $file->readable();
        }

        $data = [
            'files' => $files,
        ];

        $body = $this->view->render('fileUpload', $data);

        return new HTMLResponse(['200 OK'], $body);
    }

    public function upload(array $params = []): IResponse
    {
        $data = [];

        if (!isset($_POST)) {
            return new JSONResponse([400], ['error' => 'No files were uploaded.']);
        }

        $file = $_FILES['file'];
        $model = $this->getFileData($file);


        if ($file['error'] !== 0) {
            $data['message'] = 'Uploaded file contains errors: ' . print_r($file['error'], true) . '.';
            return new JSONResponse(['200 OK'], $data);
        }

        $data['file'] = $this->model->save($model)->readable();

        return new JSONResponse(['200 OK'], $data);
    }

    private function getFileData(mixed $file)
    {
        $fileName = explode('.', $file['name']);
        $fileExt = strtolower(end($fileName));

        if ($fileExt !== 'txt') {
            $fileMeta = getimagesize($file['tmp_name']);
        }

        $fileMeta = isset($fileMeta) ? $fileMeta[3] : '';
        $fileSize = $file['size'];

        return new FileModel($file['tmp_name'], $fileExt, $fileMeta, $fileSize);
    }
}