<?php

namespace App\Models;

use CodeIgniter\Model;

class SupportTicketModel extends Model
{
    protected $table            = 'support_tickets';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true; // created_at و updated_at را خودش پر می‌کند

    protected $allowedFields    = [
        'user_id',
        'subject',
        'category',
        'status',
    ];

    // برای جلوگیری از mass assignment ناامن، فقط همین فیلدها قابل پرشدن هستند
}
