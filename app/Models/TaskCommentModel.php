<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskCommentModel extends Model
{
    protected $table            = 'task_comments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['task_id', 'user_id', 'comment', 'mentions', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;

    public function getCommentsForTask(int $taskId)
    {
        return $this->select('task_comments.*, users.full_name, users.username, users.avatar, roles.name as role_name')
                    ->join('users', 'users.id = task_comments.user_id')
                    ->join('roles', 'roles.id = users.role_id', 'left')
                    ->where('task_comments.task_id', $taskId)
                    ->orderBy('task_comments.created_at', 'ASC')
                    ->findAll();
    }
}
