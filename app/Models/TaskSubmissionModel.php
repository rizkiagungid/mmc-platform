<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskSubmissionModel extends Model
{
    protected $table            = 'task_submissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'task_id',
        'user_id',
        'submission_text',
        'attachment_url',
        'status_id',
        'feedback',
        'grade',
        'evaluated_by',
        'submitted_at',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'submitted_at';
    protected $updatedField     = 'updated_at';

    public function getSubmissionsByTask(int $taskId)
    {
        return $this->select('task_submissions.*, users.full_name, users.username, users.avatar, users.nis_nip, users.class_dept, users.email, users.phone, users.status, users.member_uuid, roles.name as role_name, task_statuses.name as status_name, task_statuses.color as status_color, evaluator.full_name as evaluator_name')
                    ->join('users', 'users.id = task_submissions.user_id')
                    ->join('roles', 'roles.id = users.role_id', 'left')
                    ->join('task_statuses', 'task_statuses.id = task_submissions.status_id', 'left')
                    ->join('users as evaluator', 'evaluator.id = task_submissions.evaluated_by', 'left')
                    ->where('task_submissions.task_id', $taskId)
                    ->orderBy('task_submissions.submitted_at', 'DESC')
                    ->findAll();
    }

    public function getUserSubmissionForTask(int $taskId, int $userId)
    {
        return $this->select('task_submissions.*, task_statuses.name as status_name, task_statuses.color as status_color, evaluator.full_name as evaluator_name')
                    ->join('task_statuses', 'task_statuses.id = task_submissions.status_id', 'left')
                    ->join('users as evaluator', 'evaluator.id = task_submissions.evaluated_by', 'left')
                    ->where('task_submissions.task_id', $taskId)
                    ->where('task_submissions.user_id', $userId)
                    ->first();
    }

    public function getSubmissionById(int $submissionId)
    {
        return $this->select('task_submissions.*, users.full_name, users.username, users.avatar, users.nis_nip, users.class_dept, users.email, users.phone, users.status as user_status, users.member_uuid, roles.name as role_name, task_statuses.name as status_name, task_statuses.color as status_color, evaluator.full_name as evaluator_name, tasks.title as task_title, tasks.description as task_description, tasks.deadline as task_deadline, tasks.priority_id')
                    ->join('users', 'users.id = task_submissions.user_id')
                    ->join('roles', 'roles.id = users.role_id', 'left')
                    ->join('tasks', 'tasks.id = task_submissions.task_id', 'left')
                    ->join('task_statuses', 'task_statuses.id = task_submissions.status_id', 'left')
                    ->join('users as evaluator', 'evaluator.id = task_submissions.evaluated_by', 'left')
                    ->where('task_submissions.id', $submissionId)
                    ->first();
    }
}
