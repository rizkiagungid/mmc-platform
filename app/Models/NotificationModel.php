<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['user_id', 'title', 'message', 'type', 'link', 'is_read'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    /**
     * Notify single user
     */
    public function notifyUser(int $userId, string $title, string $message, string $type = 'info', ?string $link = null)
    {
        return $this->insert([
            'user_id'    => $userId,
            'title'      => $title,
            'message'    => $message,
            'type'       => $type,
            'link'       => $link,
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Notify multiple users by user IDs array
     */
    public function notifyUsers(array $userIds, string $title, string $message, string $type = 'info', ?string $link = null)
    {
        $uniqueIds = array_unique(array_filter($userIds));
        $batch = [];
        $now = date('Y-m-d H:i:s');

        foreach ($uniqueIds as $uid) {
            $batch[] = [
                'user_id'    => (int)$uid,
                'title'      => $title,
                'message'    => $message,
                'type'       => $type,
                'link'       => $link,
                'is_read'    => 0,
                'created_at' => $now,
            ];
        }

        if (!empty($batch)) {
            return $this->insertBatch($batch);
        }
        return false;
    }

    /**
     * Notify users belonging to specific role slugs (e.g. ['superadmin', 'bph', 'pembina'])
     */
    public function notifyRoles(array $roleSlugs, string $title, string $message, string $type = 'info', ?string $link = null)
    {
        $users = $this->db->table('users')
                          ->select('users.id')
                          ->join('roles', 'roles.id = users.role_id')
                          ->whereIn('roles.slug', $roleSlugs)
                          ->where('users.status', 'active')
                          ->get()->getResultArray();

        $userIds = array_column($users, 'id');
        return $this->notifyUsers($userIds, $title, $message, $type, $link);
    }

    /**
     * Get unread notification count
     */
    public function getUnreadCount(int $userId): int
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->countAllResults();
    }

    /**
     * Get unread notifications
     */
    public function getUnreadNotifications(int $userId, int $limit = 10)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }

    /**
     * Get user notifications with optional type filter
     */
    public function getUserNotifications(int $userId, ?string $type = null, int $limit = 50): array
    {
        $builder = $this->where('user_id', $userId);
        if ($type && $type !== 'all') {
            $builder->where('type', $type);
        }

        return $builder->orderBy('created_at', 'DESC')->findAll($limit);
    }
}
