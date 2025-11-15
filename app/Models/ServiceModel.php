<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table            = 'services';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'name',
        'slug',
        'description',
        'is_active',
        'order_no',
        'last_update_at',
        'last_update_reason',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getActiveServices(): array
    {
        return $this->where('is_active', 1)
                    ->orderBy('order_no', 'ASC')
                    ->findAll();
    }

    public function getActiveBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)
                    ->where('is_active', 1)
                    ->first();
    }
}

