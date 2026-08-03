<?php

namespace App\Modules\Task\Services;

use App\Services\BaseService;
use App\Models\TaskModel;
use App\Models\TaskAssigneeModel;
use App\Models\TaskSubmissionModel;
use App\Models\TaskStatusModel;
use App\Models\TaskPriorityModel;
use App\Models\UserModel;
use App\Models\AuditLogModel;

class TaskService extends BaseService
{
    protected $taskModel;
    protected $assigneeModel;
    protected $submissionModel;
    protected $statusModel;
    protected $priorityModel;
    protected $userModel;
    protected $auditLogModel;

    public function __construct()
    {
        parent::__construct();
        $this->taskModel       = new TaskModel();
        $this->assigneeModel   = new TaskAssigneeModel();
        $this->submissionModel = new TaskSubmissionModel();
        $this->statusModel     = new TaskStatusModel();
        $this->priorityModel   = new TaskPriorityModel();
        $this->userModel       = new UserModel();
        $this->auditLogModel   = new AuditLogModel();
    }

    public function getAllTasks(?int $userId = null, array $filters = []): array
    {
        return $this->taskModel->getTasksWithDetails($userId, $filters);
    }

    public function getTaskDetails(int $id): ?array
    {
        return $this->taskModel->getTaskDetails($id);
    }

    public function getAllStatuses(): array
    {
        return $this->statusModel->orderBy('sort_order', 'ASC')->findAll();
    }

    public function getAllPriorities(): array
    {
        return $this->priorityModel->orderBy('sort_order', 'ASC')->findAll();
    }

    public function getAllMembers(): array
    {
        return $this->userModel->getUsersWithRole();
    }

    public function getSubmissionsByTask(int $taskId): array
    {
        return $this->submissionModel->getSubmissionsByTask($taskId);
    }

    public function getUserSubmissionForTask(int $taskId, int $userId): ?array
    {
        return $this->submissionModel->getUserSubmissionForTask($taskId, $userId);
    }

    public function createTask(array $data, int $creatorId): array
    {
        $title       = trim($data['title'] ?? '');
        $description = trim($data['description'] ?? '');
        $priorityId  = (int)($data['priority_id'] ?? 1);
        $statusId    = (int)($data['status_id'] ?? 1);
        $deadline    = !empty($data['deadline']) ? $data['deadline'] : null;
        $assignees   = $data['assignees'] ?? [];

        if (empty($title)) {
            return $this->error('Judul tugas wajib diisi.');
        }

        if (empty($assignees) || !is_array($assignees)) {
            return $this->error('Pilih setidaknya satu anggota sebagai assignee tugas.');
        }

        $this->beginTransaction();

        try {
            $taskId = $this->taskModel->insert([
                'uuid'        => $this->taskModel->generateUuid(),
                'title'       => $title,
                'description' => $description,
                'priority_id' => $priorityId,
                'status_id'   => $statusId,
                'deadline'    => $deadline,
                'created_by'  => $creatorId,
            ]);

            // Multi-Assignees Insertion
            foreach ($assignees as $userId) {
                $this->assigneeModel->insert([
                    'task_id'     => $taskId,
                    'user_id'     => (int)$userId,
                    'assigned_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $this->auditLogModel->recordLog($creatorId, 'TASK_CREATE', "Membuat tugas baru: {$title} dengan " . count($assignees) . " assignee.");

            $this->commitTransaction();
            return $this->success('Tugas baru berhasil dibuat dan ditugaskan.', ['task_id' => $taskId]);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal membuat tugas: ' . $e->getMessage());
        }
    }

    public function updateTask(int $id, array $data, int $actorId): array
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return $this->error('Tugas tidak ditemukan.');
        }

        $title       = trim($data['title'] ?? '');
        $description = trim($data['description'] ?? '');
        $priorityId  = (int)($data['priority_id'] ?? $task['priority_id']);
        $statusId    = (int)($data['status_id'] ?? $task['status_id']);
        $deadline    = !empty($data['deadline']) ? $data['deadline'] : null;
        $assignees   = $data['assignees'] ?? [];

        if (empty($title)) {
            return $this->error('Judul tugas wajib diisi.');
        }

        if (empty($assignees) || !is_array($assignees)) {
            return $this->error('Pilih setidaknya satu anggota sebagai assignee tugas.');
        }

        $this->beginTransaction();

        try {
            $this->taskModel->update($id, [
                'title'       => $title,
                'description' => $description,
                'priority_id' => $priorityId,
                'status_id'   => $statusId,
                'deadline'    => $deadline,
            ]);

            // Sync Multi-Assignees with status_id
            $assigneeStatuses = $data['assignee_status'] ?? [];
            $this->assigneeModel->syncAssignees($id, $assignees, $assigneeStatuses);

            $this->auditLogModel->recordLog($actorId, 'TASK_UPDATE', "Perbarui tugas ID: {$id}");

            $this->commitTransaction();
            return $this->success('Tugas berhasil diperbarui.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memperbarui tugas: ' . $e->getMessage());
        }
    }

    public function deleteTask(int $id, int $actorId): array
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return $this->error('Tugas tidak ditemukan.');
        }

        $this->beginTransaction();

        try {
            $this->taskModel->delete($id);
            $this->auditLogModel->recordLog($actorId, 'TASK_DELETE', "Menghapus tugas ID {$id}: {$task['title']}");

            $this->commitTransaction();
            return $this->success('Tugas berhasil dihapus.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal menghapus tugas: ' . $e->getMessage());
        }
    }

    public function submitTask(int $taskId, int $userId, array $data, $file = null): array
    {
        $task = $this->taskModel->find($taskId);
        if (!$task) {
            return $this->error('Tugas tidak ditemukan.');
        }

        // Check if user is assigned
        $isAssigned = $this->assigneeModel->where('task_id', $taskId)->where('user_id', $userId)->first();
        if (!$isAssigned) {
            return $this->error('Anda tidak ditugaskan pada tugas ini.');
        }

        $text       = trim($data['submission_text'] ?? '');
        $link       = trim($data['attachment_url'] ?? '');
        $noLink     = !empty($data['no_link']);
        $myStatusId = !empty($data['my_status_id']) ? (int)$data['my_status_id'] : null;

        if ($noLink) {
            $link = '';
        }

        // Handle File Upload if present
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/tasks', $newName);
            $link = base_url('uploads/tasks/' . $newName);
        }

        // If no submission text/link/file is uploaded, but member requested a status update
        if (empty($text) && empty($link) && $myStatusId) {
            $this->assigneeModel->updateAssigneeStatus($taskId, $userId, $myStatusId);
            $this->auditLogModel->recordLog($userId, 'TASK_STATUS_UPDATE', "Memperbarui status tugas pribadi pada '{$task['title']}'");
            return $this->success('Status tugas Anda berhasil diperbarui.');
        }

        if (empty($text) && empty($link)) {
            return $this->error('Harap masukkan deskripsi hasil/catatan karya atau unggah berkas / tautan attachment.');
        }

        $reviewStatus = $this->statusModel->where('name', 'Review')->first();
        $statusId     = $myStatusId ?: ($reviewStatus ? $reviewStatus['id'] : 3);

        $this->beginTransaction();

        try {
            $existing = $this->submissionModel->where('task_id', $taskId)->where('user_id', $userId)->first();

            $finalLink = $link;
            if (empty($link) && !$noLink && $existing && !empty($existing['attachment_url'])) {
                $finalLink = $existing['attachment_url'];
            }

            if ($existing) {
                $this->submissionModel->update($existing['id'], [
                    'submission_text' => $text,
                    'attachment_url'  => $finalLink,
                    'status_id'       => $statusId,
                    'submitted_at'    => date('Y-m-d H:i:s'),
                ]);
            } else {
                $this->submissionModel->insert([
                    'task_id'         => $taskId,
                    'user_id'         => $userId,
                    'submission_text' => $text,
                    'attachment_url'  => $finalLink,
                    'status_id'       => $statusId,
                    'submitted_at'    => date('Y-m-d H:i:s'),
                ]);
            }

            // Update individual assignee status
            $this->assigneeModel->updateAssigneeStatus($taskId, $userId, $statusId);

            // Update main task status to Review if in Todo / In Progress
            $this->taskModel->update($taskId, ['status_id' => $statusId]);

            $this->auditLogModel->recordLog($userId, 'TASK_SUBMIT', "Pengiriman karya tugas '{$task['title']}'");

            $this->commitTransaction();
            return $this->success('Karya tugas Anda berhasil dikirim dan status diperbarui.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal mengirim karya tugas: ' . $e->getMessage());
        }
    }

    public function evaluateSubmission(int $submissionId, array $data, int $evaluatorId): array
    {
        $submission = $this->submissionModel->find($submissionId);
        if (!$submission) {
            return $this->error('Pengiriman tugas tidak ditemukan.');
        }

        $statusId = (int)($data['status_id'] ?? 5); // Default Done
        $grade    = (int)($data['grade'] ?? 100);
        $feedback = trim($data['feedback'] ?? '');
        $taskId   = (int)$submission['task_id'];
        $userId   = (int)$submission['user_id'];

        $this->beginTransaction();

        try {
            $this->submissionModel->update($submissionId, [
                'status_id'    => $statusId,
                'grade'        => $grade,
                'feedback'     => $feedback,
                'evaluated_by' => $evaluatorId,
            ]);

            // Update individual assignee status when evaluated
            $this->assigneeModel->updateAssigneeStatus($taskId, $userId, $statusId);

            // Fetch target user & status details for notification & comment cross-posting
            $targetUser = $this->userModel->find($userId);
            $statusObj  = $this->statusModel->find($statusId);
            $statusName = $statusObj ? $statusObj['name'] : 'Evaluasi';
            $taskObj    = $this->taskModel->find($taskId);

            if ($targetUser) {
                // Send notification
                $this->db->table('notifications')->insert([
                    'user_id'    => $userId,
                    'title'      => 'Evaluasi & Catatan Revisi Tugas: ' . ($taskObj['title'] ?? ''),
                    'message'    => "Pembina/BPH memberikan evaluasi (Status: {$statusName}, Nilai: {$grade}/100)" . (!empty($feedback) ? ": \"{$feedback}\"" : '.'),
                    'type'       => 'task_eval',
                    'is_read'    => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $this->auditLogModel->recordLog($evaluatorId, 'TASK_EVALUATE', "Evaluasi pengiriman tugas ID {$submissionId} (Nilai: {$grade})");

            $this->commitTransaction();
            return $this->success('Evaluasi karya berhasil disimpan.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal menyimpan evaluasi: ' . $e->getMessage());
        }
    }

    public function addComment(int $taskId, int $userId, string $comment): array
    {
        $comment = trim($comment);
        if (empty($comment)) {
            return $this->error('Komentar tidak boleh kosong.');
        }

        $task = $this->taskModel->find($taskId);
        if (!$task) {
            return $this->error('Tugas tidak ditemukan.');
        }

        // Extract mentions: @username
        preg_match_all('/@([a-zA-Z0-9_\.\-]+)/', $comment, $matches);
        $mentionedUsernames = array_unique($matches[1] ?? []);

        $mentionedUserIds = [];
        if (!empty($mentionedUsernames)) {
            $users = $this->userModel->whereIn('username', $mentionedUsernames)->findAll();
            foreach ($users as $u) {
                $mentionedUserIds[] = $u['id'];

                $this->db->table('notifications')->insert([
                    'user_id'    => $u['id'],
                    'title'      => 'Mention di Tugas: ' . $task['title'],
                    'message'    => 'Anda disebutkan dalam diskusi tugas: ' . $task['title'],
                    'type'       => 'mention',
                    'is_read'    => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $commentModel = new \App\Models\TaskCommentModel();
        $commentModel->insert([
            'task_id'    => $taskId,
            'user_id'    => $userId,
            'comment'    => $comment,
            'mentions'   => !empty($mentionedUserIds) ? json_encode($mentionedUserIds) : null,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->auditLogModel->recordLog($userId, 'TASK_COMMENT_ADD', "Menambahkan komentar pada tugas ID {$taskId}");

        return $this->success('Komentar berhasil ditambahkan.');
    }

    public function getTaskComments(int $taskId): array
    {
        $commentModel = new \App\Models\TaskCommentModel();
        return $commentModel->getCommentsForTask($taskId);
    }
}
