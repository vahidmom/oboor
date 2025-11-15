<?php

namespace App\Models;

use CodeIgniter\Model;

class ServerMetaModel extends Model
{
    protected $table            = 'server_meta';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'server_id',
        'meta_key',
        'meta_value',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getMetaForServer(int $serverId): array
    {
        $rows = $this->where('server_id', $serverId)->findAll();

        $meta = [];
        foreach ($rows as $row) {
            $meta[$row['meta_key']] = $row['meta_value'];
        }

        return $meta;
    }
}
