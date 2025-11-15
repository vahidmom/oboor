<?php

namespace App\Models;

use CodeIgniter\Model;

class ConnectionTutorialModel extends Model
{
    protected $table      = 'connection_tutorials';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'group_id', 'title', 'slug', 'short_description',
        'content', 'video_path', 'video_embed',
        'sort_order', 'is_active', 'view_count',
        'min_user_level', 'created_at', 'updated_at',
    ];

    protected $useTimestamps = true;

    public function incrementViewCount(int $id)
    {
        return $this->where('id', $id)
                    ->set('view_count', 'view_count + 1', false)
                    ->update();
    }

    public function findBySlug(string $slug)
    {
        return $this->where('slug', $slug)
                    ->where('is_active', 1)
                    ->first();
    }
}
