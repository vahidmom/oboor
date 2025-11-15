<?php

namespace App\Models;

use CodeIgniter\Model;

class ServerModel extends Model
{
    protected $table            = 'servers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'service_id',
        'name',
        'hostname',
        'port',
        'country',
        'is_active',
        'order_no',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getActiveByServiceId(int $serviceId): array
    {
        return $this->where('service_id', $serviceId)
                    ->where('is_active', 1)
                    ->orderBy('order_no', 'ASC')
                    ->findAll();
    }

    public function getActiveById(int $id): ?array
    {
        return $this->where('id', $id)
                    ->where('is_active', 1)
                    ->first();
    }
}
