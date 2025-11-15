<?php

namespace App\Models;

use CodeIgniter\Model;

class UserChangeLogModel extends Model
{
    protected $table      = 'user_change_logs';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'user_id',
        'action_key',
        'title',
        'description',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    public $timestamps   = false; // چون created_at را خودمان مقدار می‌دهیم
}
