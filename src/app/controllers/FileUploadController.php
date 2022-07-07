<?php

namespace App\controllers;

use App\Core\Controller;
use App\core\Responses\HTMLResponse;
use App\core\Responses\IResponse;
use App\core\Responses\JSONResponse;
use App\models\FileModel;

class FileUploadController extends Controller
{
    public function index(): IResponse
    {
        $files = [];

        foreach ($this->model->fetchAll() as $file) {
            $files[] = $file->toReadable();
        }

        $data = [
            'files' => $files,
        ];

        $body = $this->view->render('fileUpload', $data);

        return new HTMLResponse(['200 OK'], $body);
    }

    public function upload(): IResponse
    {
        $data = [];

        if (!isset($_FILES['file'])) {
            return new JSONResponse([400], ['error' => 'No files were uploaded.']);
        }

        $file = $_FILES['file'];
        $model = FileModel::createFromFile($file);


        if ($file['error'] !== 0) {
            $data['message'] = 'Uploaded file contains errors: ' . print_r($file['error'], true) . '.';
            return new JSONResponse(['200 OK'], $data);
        }

        $data['file'] = $this->model->save($model)->toReadable();

        return new JSONResponse(['200 OK'], $data);
    }
}