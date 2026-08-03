<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskActivityModel extends Model
{
    protected $table            = 'task_activities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['task_id', 'user_id', 'action', 'description'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    public function logActivity(int $taskId, ?int $userId, string $action, string $description)
    {
        return $this->insert([
            'task_id'     => $taskId,
            'user_id'     => $userId,
            'action'      => $action,
            'description' => $description,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    public function getActivitiesForTask(int $taskId)
    {
        return $this->select('task_activities.*, users.full_name, users.avatar, users.username')
                    ->join('users', 'users.id = task_activities.user_id', 'left')
                    ->where('task_activities.task_id', $taskId)
                    ->orderBy('task_activities.created_at', 'DESC')
                    ->findAll();
    }
}
