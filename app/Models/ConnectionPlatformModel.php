<?php

namespace App\Models;

use CodeIgniter\Model;

class ConnectionPlatformModel extends Model
{
    protected $table      = 'connection_platforms';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name', 'slug', 'sort_order', 'is_active',
        'created_at', 'updated_at',
    ];

    protected $useTimestamps = true; // created_at / updated_at
}
