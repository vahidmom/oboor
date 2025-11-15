<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'data'];
    public $timestamps = false;

    public function getSetting($name)
    {
        return $this->where('name', $name)->first()['data'] ?? null;
    }
}
