<?php

namespace App\Models;

use CodeIgniter\Model;

class SupportMessageModel extends Model
{
    protected $table            = 'support_messages';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    public    $useTimestamps    = false; // چون خودمان created_at را ست می‌کنیم

    protected $allowedFields    = [
        'ticket_id',
        'sender_type',
        'message',
        'created_at',
    ];
}
