<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskAssigneeModel extends Model
{
    protected $table            = 'task_assignees';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['task_id', 'user_id', 'status_id', 'assigned_at'];
    protected $useTimestamps    = false;

    public function syncAssignees(int $taskId, array $userIds, array $assigneeStatuses = [])
    {
        // Fetch existing assignees to preserve status_id
        $existing = $this->where('task_id', $taskId)->findAll();
        $existingStatusMap = [];
        foreach ($existing as $e) {
            $existingStatusMap[$e['user_id']] = $e['status_id'] ?? 1;
        }

        // Clear existing assignees for this task
        $this->where('task_id', $taskId)->delete();

        $now = date('Y-m-d H:i:s');
        $data = [];
        foreach ($userIds as $uid) {
            $uid = (int)$uid;
            if ($uid > 0) {
                $statusId = $assigneeStatuses[$uid] ?? ($existingStatusMap[$uid] ?? 1);
                $data[] = [
                    'task_id'     => $taskId,
                    'user_id'     => $uid,
                    'status_id'   => (int)$statusId,
                    'assigned_at' => $now,
                ];
            }
        }

        if (!empty($data)) {
            $this->insertBatch($data);
        }

        return true;
    }

    public function updateAssigneeStatus(int $taskId, int $userId, int $statusId)
    {
        $existing = $this->where('task_id', $taskId)->where('user_id', $userId)->first();
        if ($existing) {
            return $this->where('id', $existing['id'])->set(['status_id' => $statusId])->update();
        }

        return $this->insert([
            'task_id'     => $taskId,
            'user_id'     => $userId,
            'status_id'   => $statusId,
            'assigned_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
