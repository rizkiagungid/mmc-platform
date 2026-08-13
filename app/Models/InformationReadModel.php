<?php

namespace App\Models;

use CodeIgniter\Model;

class InformationReadModel extends Model
{
    protected $table            = 'information_reads';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['information_id', 'user_id', 'read_at'];
    protected $useTimestamps    = false;

    /**
     * Mark an information item as read by a user
     */
    public function markAsRead(int $informationId, int $userId): bool
    {
        $existing = $this->where('information_id', $informationId)
                         ->where('user_id', $userId)
                         ->first();

        if (!$existing) {
            return (bool) $this->insert([
                'information_id' => $informationId,
                'user_id'        => $userId,
                'read_at'        => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    /**
     * Check if user has read an information
     */
    public function isRead(int $informationId, int $userId): bool
    {
        $count = $this->where('information_id', $informationId)
                      ->where('user_id', $userId)
                      ->countAllResults();

        return $count > 0;
    }
}
