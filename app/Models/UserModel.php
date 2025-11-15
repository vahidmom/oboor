<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $returnType     = 'array'; // اگر entity استفاده می‌کنی، این را تغییر بده
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'email',
        'mobile',
        'password_hash',
        'avatar',
        'user_level',
        'phone_verified',
        'email_verified',
        'last_login_at',
        'last_login_ip',
        'last_password_change_at',
      'user_bio',
	  'total_orders',
        'total_spent',
        'discount_percent',
		'notify_email',
        'notify_sms',
        'notify_email_newsletter',
        'notify_sms_newsletter',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
