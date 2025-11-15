<?php

namespace App\Models;

use CodeIgniter\Model;

class UserMobileOtpModel extends Model
{
    protected $table      = 'user_mobile_otps';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'user_id',
        'mobile',
        'code',
        'expires_at',
        'used_at',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    public $timestamps = false;
}
