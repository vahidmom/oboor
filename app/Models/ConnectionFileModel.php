<?php

namespace App\Models;

use CodeIgniter\Model;

class ConnectionFileModel extends Model
{
    protected $table      = 'connection_files';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tutorial_id', 'title', 'file_path', 'file_size',
        'version', 'change_log', 'sort_order',
        'is_active', 'download_count', 'created_at', 'updated_at',
    ];

    protected $useTimestamps = true;

    public function incrementDownloadCount(int $id)
    {
        return $this->where('id', $id)
                    ->set('download_count', 'download_count + 1', false)
                    ->update();
    }

    public function findActiveByTutorial(int $tutorialId)
    {
        return $this->where('tutorial_id', $tutorialId)
                    ->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }
}
