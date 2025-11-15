<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\ConnectionFileModel;
use App\Models\ConnectionTutorialModel;

class DownloadController extends BaseController
{
    protected $fileModel;
    protected $tutorialModel;

    public function __construct()
    {
        $this->fileModel     = new ConnectionFileModel();
        $this->tutorialModel = new ConnectionTutorialModel();
    }

    public function file($id)
    {
        $file = $this->fileModel
            ->where('id', $id)
            ->where('is_active', 1)
            ->first();

        if (! $file) {
            // اگر اینجا 404 می‌بینی یعنی رکورد فایل با این ID وجود ندارد
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $tutorial = $this->tutorialModel
            ->where('id', $file['tutorial_id'])
            ->where('is_active', 1)
            ->first();

        if (! $tutorial) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $filePath = WRITEPATH . 'uploads/downloads/' . $file['file_path'];

        if (! is_file($filePath)) {
            // این هم 404 تولید می‌کند، ولی به خاطر نبودن فایل فیزیکی روی دیسک
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->fileModel->incrementDownloadCount((int) $id);

        return $this->response->download($filePath, null);
    }
}
