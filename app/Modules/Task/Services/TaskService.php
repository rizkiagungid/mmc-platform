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
        return $this->userModel->select('users.*, roles.name as role_name, roles.slug as role_slug')
                               ->join('roles', 'roles.id = users.role_id')
                               ->whereNotIn('roles.slug', ['superadmin', 'alumni'])
                               ->where('users.status', 'active')
                               ->orderBy('users.full_name', 'ASC')
                               ->findAll();
    }

    public function getSubmissionsByTask(int $taskId): array
    {
        return $this->submissionModel->getSubmissionsByTask($taskId);
    }

    public function getUserSubmissionForTask(int $taskId, int $userId): ?array
    {
        return $this->submissionModel->getUserSubmissionForTask($taskId, $userId);
    }

    private function filterAssignableUserIds(array $assigneeIds): array
    {
        $assigneeIds = array_filter(array_map('intval', $assigneeIds));
        if (empty($assigneeIds)) {
            return [];
        }

        $validUsers = $this->userModel->select('users.id')
                                      ->join('roles', 'roles.id = users.role_id')
                                      ->whereIn('users.id', $assigneeIds)
                                      ->whereNotIn('roles.slug', ['superadmin', 'alumni'])
                                      ->where('users.status', 'active')
                                      ->findAll();

        return array_column($validUsers, 'id');
    }

    public function createTask(array $data, int $creatorId): array
    {
        $title       = trim($data['title'] ?? '');
        $description = trim($data['description'] ?? '');
        $priorityId  = (int)($data['priority_id'] ?? 1);
        $statusId    = (int)($data['status_id'] ?? 1);
        $deadline    = !empty($data['deadline']) ? $data['deadline'] : null;
        $assignees   = $this->filterAssignableUserIds($data['assignees'] ?? []);

        if (empty($title)) {
            return $this->error('Judul tugas wajib diisi.');
        }

        if (empty($assignees)) {
            return $this->error('Pilih setidaknya satu anggota aktif (selain Super Admin dan Alumni) sebagai penerima tugas.');
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
        $assignees   = $this->filterAssignableUserIds($data['assignees'] ?? []);

        if (empty($title)) {
            return $this->error('Judul tugas wajib diisi.');
        }

        if (empty($assignees)) {
            return $this->error('Pilih setidaknya satu anggota aktif (selain Super Admin dan Alumni) sebagai penerima tugas.');
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
            // Clean up any uploaded submission files for this task from server disk
            $submissions = $this->submissionModel->where('task_id', $id)->findAll();
            foreach ($submissions as $sub) {
                if (!empty($sub['attachment_url']) && strpos($sub['attachment_url'], 'uploads/tasks/') !== false) {
                    $filename = basename(parse_url($sub['attachment_url'], PHP_URL_PATH));
                    $filePath = ROOTPATH . 'public/uploads/tasks/' . $filename;
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }

            $this->taskModel->delete($id);
            $this->auditLogModel->recordLog($actorId, 'TASK_DELETE', "Menghapus tugas ID {$id}: {$task['title']}");

            $this->commitTransaction();
            return $this->success('Tugas berhasil dihapus.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal menghapus tugas: ' . $e->getMessage());
        }
    }

    public function getSubmissionById(int $submissionId)
    {
        return $this->submissionModel->getSubmissionById($submissionId);
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

        $existing = $this->submissionModel->where('task_id', $taskId)->where('user_id', $userId)->first();

        $text = trim($data['submission_text'] ?? '');
        $link = trim($data['attachment_url'] ?? '');

        // Handle File Upload if present
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old uploaded file from server disk if user uploaded a replacement
            if ($existing && !empty($existing['attachment_url']) && strpos($existing['attachment_url'], 'uploads/tasks/') !== false) {
                $oldFilename = basename(parse_url($existing['attachment_url'], PHP_URL_PATH));
                $oldFilePath = ROOTPATH . 'public/uploads/tasks/' . $oldFilename;
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }
            }

            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/tasks', $newName);
            $link = 'uploads/tasks/' . $newName;
        }

        // If everything is completely blank, provide a fallback text
        if (empty($text) && empty($link) && (!$existing || empty($existing['attachment_url']))) {
            $text = 'Tugas telah dikirimkan oleh anggota.';
        }

        // Automatic Status: 3 (In Review / Sedang Ditinjau)
        $statusId = 3;

        $this->beginTransaction();

        try {
            $finalLink = !empty($link) ? clean_media_path($link) : ($existing['attachment_url'] ?? '');

            if ($existing) {
                $this->submissionModel->update($existing['id'], [
                    'submission_text' => !empty($text) ? $text : $existing['submission_text'],
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

            // Automatically update individual assignee status to 3 (In Review)
            $this->assigneeModel->updateAssigneeStatus($taskId, $userId, $statusId);

            // Automatically update main task status to In Review if currently in Todo / In Progress
            if ($task['status_id'] <= 2) {
                $this->taskModel->update($taskId, ['status_id' => $statusId]);
            }

            $this->auditLogModel->recordLog($userId, 'TASK_SUBMIT', "Pengiriman tugas '{$task['title']}'");

            $this->commitTransaction();
            return $this->success('Tugas anda berhasil dikirim!');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal mengirim tugas: ' . $e->getMessage());
        }
    }

    public function deleteAttachment(int $taskId, int $userId): array
    {
        $submission = $this->submissionModel->where('task_id', $taskId)->where('user_id', $userId)->first();
        if (!$submission || empty($submission['attachment_url'])) {
            return $this->error('Berkas lampiran tidak ditemukan.');
        }

        $url = $submission['attachment_url'];

        // If local file in uploads/tasks, delete from disk
        if (strpos($url, 'uploads/tasks/') !== false) {
            $filename = basename(parse_url($url, PHP_URL_PATH));
            $filePath = ROOTPATH . 'public/uploads/tasks/' . $filename;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $this->submissionModel->update($submission['id'], [
            'attachment_url' => '',
        ]);

        $this->auditLogModel->recordLog($userId, 'TASK_ATTACHMENT_DELETE', "Menghapus berkas lampiran tugas ID {$taskId}");

        return $this->success('Berkas lampiran tugas berhasil dihapus.');
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
                // Send notification with direct link to task detail
                $notificationModel = new \App\Models\NotificationModel();
                $notificationModel->notifyUser(
                    $userId,
                    'Evaluasi & Catatan Revisi Tugas: ' . ($taskObj['title'] ?? ''),
                    "Pembina/BPH memberikan evaluasi (Status: {$statusName}, Nilai: {$grade}/100)" . (!empty($feedback) ? ": \"{$feedback}\"" : '.'),
                    'task',
                    'member/tasks/submit/' . $taskId
                );
            }

            $this->auditLogModel->recordLog($evaluatorId, 'TASK_EVALUATE', "Evaluasi pengiriman tugas ID {$submissionId} (Nilai: {$grade})");

            $this->commitTransaction();
            return $this->success('Evaluasi tugas berhasil disimpan.');
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

        $notificationModel = new \App\Models\NotificationModel();
        $mentionedUserIds  = [];
        if (!empty($mentionedUsernames)) {
            $users = $this->userModel->whereIn('username', $mentionedUsernames)->findAll();
            foreach ($users as $u) {
                $mentionedUserIds[] = $u['id'];

                $notificationModel->notifyUser(
                    $u['id'],
                    'Mention di Tugas: ' . $task['title'],
                    'Anda disebutkan dalam diskusi tugas: ' . $task['title'],
                    'task',
                    'member/tasks/submit/' . $taskId
                );
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
