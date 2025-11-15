<?php

namespace App\Models;

use CodeIgniter\Model;

class SupportAttachmentModel extends Model
{
    protected $table            = 'support_attachments';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'ticket_id',
        'message_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
