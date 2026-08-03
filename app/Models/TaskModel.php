<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table            = 'tasks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'uuid',
        'title',
        'description',
        'priority_id',
        'status_id',
        'deadline',
        'created_by',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    public function generateUuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function getTasksWithDetails($userId = null, array $filters = [])
    {
        $builder = $this->select('tasks.*, task_priorities.name as priority_name, task_priorities.color as priority_color, users.full_name as creator_name')
                        ->join('task_priorities', 'task_priorities.id = tasks.priority_id')
                        ->join('users', 'users.id = tasks.created_by', 'left');

        if ($userId) {
            $builder->join('task_assignees', 'task_assignees.task_id = tasks.id')
                    ->where('task_assignees.user_id', $userId);
        }

        if (!empty($filters['priority_id'])) {
            $builder->where('tasks.priority_id', (int)$filters['priority_id']);
        }

        if (!empty($filters['status_id'])) {
            $builder->join('task_assignees as ta_fltr', 'ta_fltr.task_id = tasks.id', 'left')
                    ->where('ta_fltr.status_id', (int)$filters['status_id']);
        }

        if (!empty($filters['keyword'])) {
            $kw = $filters['keyword'];
            $builder->groupStart()
                    ->like('tasks.title', $kw)
                    ->orLike('tasks.description', $kw)
                    ->groupEnd();
        }

        if (!empty($filters['deadline_filter'])) {
            $today = date('Y-m-d');
            if ($filters['deadline_filter'] === 'today') {
                $builder->where('DATE(tasks.deadline)', $today);
            } elseif ($filters['deadline_filter'] === 'overdue') {
                $builder->where('tasks.deadline <', date('Y-m-d H:i:s'))
                        ->where('tasks.deadline IS NOT NULL');
            } elseif ($filters['deadline_filter'] === 'upcoming') {
                $builder->where('tasks.deadline >=', date('Y-m-d H:i:s'));
            }
        }

        $tasks = $builder->groupBy('tasks.id')->orderBy('tasks.created_at', 'DESC')->findAll();

        foreach ($tasks as &$task) {
            $task['assignees'] = $this->db->table('task_assignees')
                                         ->select('users.id, users.full_name, users.avatar, users.nis_nip, task_assignees.status_id, task_statuses.name as status_name, task_statuses.color as status_color')
                                         ->join('users', 'users.id = task_assignees.user_id')
                                         ->join('task_statuses', 'task_statuses.id = task_assignees.status_id', 'left')
                                         ->where('task_assignees.task_id', $task['id'])
                                         ->get()->getResultArray();

            $task['labels'] = $this->db->table('task_labels')
                                      ->select('labels.id, labels.name, labels.color')
                                      ->join('labels', 'labels.id = task_labels.label_id')
                                      ->where('task_labels.task_id', $task['id'])
                                      ->get()->getResultArray();

            if ($userId) {
                $submission = $this->db->table('task_submissions')
                                       ->where('task_id', $task['id'])
                                       ->where('user_id', $userId)
                                       ->get()->getRowArray();
                $task['my_submission'] = $submission;
                $task['is_submitted']  = !empty($submission);

                $myAssignee = array_filter($task['assignees'], function($a) use ($userId) {
                    return $a['id'] == $userId;
                });
                if (!empty($myAssignee)) {
                    $myAss = reset($myAssignee);
                    $task['my_status_name']  = $myAss['status_name'] ?? 'Todo';
                    $task['my_status_color'] = $myAss['status_color'] ?? '#3b82f6';
                    $task['my_status_id']    = $myAss['status_id'] ?? 1;
                }
            } else {
                $task['my_submission'] = null;
                $task['is_submitted']  = false;
            }
        }

        return $tasks;
    }

    public function getTaskDetails(int $id)
    {
        $task = $this->select('tasks.*, task_statuses.name as status_name, task_statuses.color as status_color, task_priorities.name as priority_name, task_priorities.color as priority_color, users.full_name as creator_name')
                     ->join('task_statuses', 'task_statuses.id = tasks.status_id')
                     ->join('task_priorities', 'task_priorities.id = tasks.priority_id')
                     ->join('users', 'users.id = tasks.created_by', 'left')
                     ->where('tasks.id', $id)
                     ->first();

        if ($task) {
            $task['assignees'] = $this->db->table('task_assignees')
                                         ->select('users.id, users.full_name, users.username, users.avatar, users.nis_nip, users.class_dept, task_assignees.status_id, task_statuses.name as status_name, task_statuses.color as status_color')
                                         ->join('users', 'users.id = task_assignees.user_id')
                                         ->join('task_statuses', 'task_statuses.id = task_assignees.status_id', 'left')
                                         ->where('task_assignees.task_id', $task['id'])
                                         ->get()->getResultArray();

            $task['labels'] = $this->db->table('task_labels')
                                      ->select('labels.id, labels.name, labels.color')
                                      ->join('labels', 'labels.id = task_labels.label_id')
                                      ->where('task_labels.task_id', $task['id'])
                                      ->get()->getResultArray();
        }

        return $task;
    }
}
