<?php

namespace App\Models;

use CodeIgniter\Model;

class ConnectionGroupModel extends Model
{
    protected $table      = 'connection_groups';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'platform_id', 'title', 'slug', 'description',
        'sort_order', 'is_active', 'created_at', 'updated_at',
    ];

    protected $useTimestamps = true;
}
