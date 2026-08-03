<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['user_id', 'action', 'description', 'ip_address', 'user_agent'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    public function recordLog(?int $userId, string $action, string $description, ?string $ip = null, ?string $agent = null)
    {
        $request = \Config\Services::request();
        return $this->insert([
            'user_id'     => $userId,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => $ip ?? $request->getIPAddress(),
            'user_agent'  => $agent ?? (string) $request->getUserAgent(),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }
}
