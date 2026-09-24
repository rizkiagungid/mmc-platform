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

    public function getUserById(int $id): ?array
    {
        return $this->userModel->select('users.*, roles.name as role_name, roles.slug as role_slug')
                               ->join('roles', 'roles.id = users.role_id', 'left')
                               ->where('users.id', $id)
                               ->first();
    }

    public function getSubmissionsByTask(int $taskId): array
    {
        // 1. Fetch all assignees with their status and left join with task_submissions
        $assignees = $this->db->table('task_assignees')
            ->select('
                users.id as user_id, 
                users.full_name, 
                users.username, 
                users.avatar, 
                users.nis_nip, 
                users.class_dept, 
                users.email, 
                users.phone, 
                users.status as user_status, 
                users.member_uuid, 
                roles.name as role_name, 
                task_assignees.status_id as assignee_status_id,
                ta_status.name as assignee_status_name,
                ta_status.color as assignee_status_color,
                task_submissions.id as submission_id,
                task_submissions.submission_text,
                task_submissions.attachment_url,
                task_submissions.status_id as submission_status_id,
                sub_status.name as submission_status_name,
                sub_status.color as submission_status_color,
                task_submissions.grade,
                task_submissions.feedback,
                task_submissions.evaluated_by,
                task_submissions.submitted_at,
                evaluator.full_name as evaluator_name
            ')
            ->join('users', 'users.id = task_assignees.user_id')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->join('task_statuses as ta_status', 'ta_status.id = task_assignees.status_id', 'left')
            ->join('task_submissions', 'task_submissions.task_id = task_assignees.task_id AND task_submissions.user_id = task_assignees.user_id', 'left')
            ->join('task_statuses as sub_status', 'sub_status.id = task_submissions.status_id', 'left')
            ->join('users as evaluator', 'evaluator.id = task_submissions.evaluated_by', 'left')
            ->where('task_assignees.task_id', $taskId)
            ->orderBy('task_submissions.submitted_at', 'DESC')
            ->orderBy('users.full_name', 'ASC')
            ->get()->getResultArray();

        // 2. Also check if any user submitted who is not explicitly in task_assignees
        $submissionUserIds = array_column($assignees, 'user_id');
        $builder = $this->submissionModel->select('
                users.id as user_id, 
                users.full_name, 
                users.username, 
                users.avatar, 
                users.nis_nip, 
                users.class_dept, 
                users.email, 
                users.phone, 
                users.status as user_status, 
                users.member_uuid, 
                roles.name as role_name, 
                task_submissions.status_id as assignee_status_id,
                task_statuses.name as assignee_status_name,
                task_statuses.color as assignee_status_color,
                task_submissions.id as submission_id,
                task_submissions.submission_text,
                task_submissions.attachment_url,
                task_submissions.status_id as submission_status_id,
                task_statuses.name as submission_status_name,
                task_statuses.color as submission_status_color,
                task_submissions.grade,
                task_submissions.feedback,
                task_submissions.evaluated_by,
                task_submissions.submitted_at,
                evaluator.full_name as evaluator_name
            ')
            ->join('users', 'users.id = task_submissions.user_id')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->join('task_statuses', 'task_statuses.id = task_submissions.status_id', 'left')
            ->join('users as evaluator', 'evaluator.id = task_submissions.evaluated_by', 'left')
            ->where('task_submissions.task_id', $taskId);
        
        if (!empty($submissionUserIds)) {
            $builder->whereNotIn('task_submissions.user_id', $submissionUserIds);
        }
        $extraSubmissions = $builder->findAll();

        $all = array_merge($assignees, $extraSubmissions);

        // Normalize helper fields so views can access properties seamlessly
        foreach ($all as &$item) {
            $item['id'] = $item['submission_id'] ?? null;
            $item['status_id'] = $item['assignee_status_id'] ?? ($item['submission_status_id'] ?? 1);
            $item['status_name'] = $item['assignee_status_name'] ?? ($item['submission_status_name'] ?? 'Belum dikerjakan');
            $item['status_color'] = $item['assignee_status_color'] ?? ($item['submission_status_color'] ?? '#6c757d');
            $item['has_submitted'] = !empty($item['submission_id']) || !empty($item['submitted_at']);
        }

        return $all;
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

            // Multi-Assignees Insertion (Optional)
            foreach ($assignees as $userId) {
                $this->assigneeModel->insert([
                    'task_id'     => $taskId,
                    'user_id'     => (int)$userId,
                    'assigned_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $assigneeCount = count($assignees);
            $this->auditLogModel->recordLog($creatorId, 'TASK_CREATE', "Membuat tugas baru: {$title} dengan {$assigneeCount} assignee.");

            $this->commitTransaction();
            return $this->success('Tugas baru berhasil dibuat' . ($assigneeCount > 0 ? ' dan ditugaskan.' : '.'), ['task_id' => $taskId]);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal membuat tugas: ' . $e->getMessage());
        }
    }

    public function duplicateTask(int $id, int $creatorId, array $options = []): array
    {
        $original = $this->getTaskDetails($id);
        if (!$original) {
            return $this->error('Tugas yang ingin diduplikasi tidak ditemukan.');
        }

        // Custom or default Title
        $newTitle = !empty($options['title']) ? trim($options['title']) : trim($original['title']) . ' (Copy)';

        // Include or override Description
        if (isset($options['copy_description']) && empty($options['copy_description'])) {
            $newDescription = '';
        } elseif (isset($options['description'])) {
            $newDescription = trim($options['description']);
        } else {
            $newDescription = $original['description'] ?? '';
        }

        // Priority
        $newPriorityId = !empty($options['priority_id']) ? (int)$options['priority_id'] : (int)($original['priority_id'] ?? 1);

        // Status
        $newStatusId   = !empty($options['status_id']) ? (int)$options['status_id'] : 1;

        // Deadline
        if (isset($options['copy_deadline']) && empty($options['copy_deadline'])) {
            $newDeadline = null;
        } elseif (!empty($options['deadline'])) {
            $newDeadline = $options['deadline'];
        } else {
            $newDeadline = $original['deadline'] ?? null;
        }

        // Filter / Select Assignees
        // If copy_assignees is explicitly unchecked or '0', do not copy assignees
        $shouldCopyAssignees = isset($options['copy_assignees'])
            ? (!empty($options['copy_assignees']) && $options['copy_assignees'] !== '0')
            : !empty($options['assignees']);

        if (!$shouldCopyAssignees) {
            $assignees = [];
        } elseif (isset($options['assignees'])) {
            $assignees = $this->filterAssignableUserIds($options['assignees']);
        } else {
            // Default from original task if not passed
            $originalAssignees = $original['assignees'] ?? [];
            $assignees = array_column($originalAssignees, 'id');
        }

        if (empty($newTitle)) {
            return $this->error('Judul tugas duplikasi tidak boleh kosong.');
        }

        $this->beginTransaction();

        try {
            // 1. Insert new Duplicated Task
            $newTaskId = $this->taskModel->insert([
                'uuid'        => $this->taskModel->generateUuid(),
                'title'       => $newTitle,
                'description' => $newDescription,
                'priority_id' => $newPriorityId,
                'status_id'   => $newStatusId,
                'deadline'    => $newDeadline,
                'created_by'  => $creatorId,
            ]);

            // 2. Insert Filtered Assignees
            foreach ($assignees as $userId) {
                $this->assigneeModel->insert([
                    'task_id'     => $newTaskId,
                    'user_id'     => (int)$userId,
                    'assigned_at' => date('Y-m-d H:i:s'),
                    'status_id'   => 1, // Fresh 'Belum dikerjakan' status
                ]);
            }

            // 3. Duplicate Task Labels if requested
            $copyLabels = !isset($options['copy_labels']) || !empty($options['copy_labels']);
            if ($copyLabels) {
                $labels = $original['labels'] ?? [];
                foreach ($labels as $l) {
                    $this->db->table('task_labels')->insert([
                        'task_id'  => $newTaskId,
                        'label_id' => (int)$l['id'],
                    ]);
                }
            }

            $this->auditLogModel->recordLog($creatorId, 'TASK_DUPLICATE', "Menduplikasi tugas ID #{$id} ({$original['title']}) menjadi ID #{$newTaskId} ({$newTitle}) dengan " . count($assignees) . " assignee.");

            $this->commitTransaction();

            return $this->success("Tugas berhasil diduplikasi menjadi '{$newTitle}'.", ['task_id' => $newTaskId]);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal menduplikasi tugas: ' . $e->getMessage());
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

        $this->beginTransaction();

        try {
            $this->taskModel->update($id, [
                'title'       => $title,
                'description' => $description,
                'priority_id' => $priorityId,
                'status_id'   => $statusId,
                'deadline'    => $deadline,
            ]);

            // Sync Multi-Assignees with status_id (Can be empty)
            $assigneeStatuses = $data['assignee_status'] ?? [];
            $this->assigneeModel->syncAssignees($id, $assignees, $assigneeStatuses);

            $assigneeCount = count($assignees);
            $this->auditLogModel->recordLog($actorId, 'TASK_UPDATE', "Perbarui tugas ID: {$id} dengan {$assigneeCount} assignee.");

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

    public function updateAssigneeStatus(int $taskId, int $userId, int $statusId, int $actorId): array
    {
        $task = $this->taskModel->find($taskId);
        if (!$task) {
            return $this->error('Tugas tidak ditemukan.');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->error('Anggota tidak ditemukan.');
        }

        $this->beginTransaction();

        try {
            // Update individual assignee status
            $this->assigneeModel->updateAssigneeStatus($taskId, $userId, $statusId);

            // Automatically create or update submission entry so it's seamlessly listed
            $existing = $this->submissionModel->where('task_id', $taskId)->where('user_id', $userId)->first();
            $now = date('Y-m-d H:i:s');

            if (!$existing) {
                $this->submissionModel->insert([
                    'task_id'         => $taskId,
                    'user_id'         => $userId,
                    'submission_text' => 'Ditandai oleh Pembina / Admin',
                    'attachment_url'  => '',
                    'status_id'       => $statusId,
                    'submitted_at'    => $now,
                ]);
            } else {
                $this->submissionModel->update($existing['id'], [
                    'status_id' => $statusId,
                ]);
            }

            $statusObj  = $this->statusModel->find($statusId);
            $statusName = $statusObj ? $statusObj['name'] : "Status #{$statusId}";

            $this->auditLogModel->recordLog($actorId, 'TASK_ASSIGNEE_STATUS_UPDATE', "Perbarui status anggota {$user['full_name']} pada tugas ID {$taskId} menjadi {$statusName}");

            $this->commitTransaction();
            return $this->success("Status anggota {$user['full_name']} berhasil diperbarui menjadi '{$statusName}'.");
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memperbarui status anggota: ' . $e->getMessage());
        }
    }

    public function evaluateAssigneeDirectly(int $taskId, int $userId, array $data, int $evaluatorId): array
    {
        $task = $this->taskModel->find($taskId);
        if (!$task) {
            return $this->error('Tugas tidak ditemukan.');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->error('Anggota tidak ditemukan.');
        }

        $grade    = (isset($data['grade']) && $data['grade'] !== '' && $data['grade'] !== null) ? max(0, min(100, (int)$data['grade'])) : null;
        $statusId = !empty($data['status_id']) ? (int)$data['status_id'] : 5; // Default Done (5)
        $feedback = trim($data['feedback'] ?? '');

        $this->beginTransaction();

        try {
            $existing = $this->submissionModel->where('task_id', $taskId)->where('user_id', $userId)->first();
            $now = date('Y-m-d H:i:s');

            if ($existing) {
                $this->submissionModel->update($existing['id'], [
                    'status_id'    => $statusId,
                    'grade'        => $grade,
                    'feedback'     => $feedback,
                    'evaluated_by' => $evaluatorId,
                ]);
                $submissionId = $existing['id'];
            } else {
                $submissionId = $this->submissionModel->insert([
                    'task_id'         => $taskId,
                    'user_id'         => $userId,
                    'submission_text' => !empty($feedback) ? 'Dinilai langsung oleh Pembina / Admin' : 'Selesai / Dinilai langsung oleh Admin',
                    'attachment_url'  => '',
                    'status_id'       => $statusId,
                    'grade'           => $grade,
                    'feedback'        => $feedback,
                    'evaluated_by'    => $evaluatorId,
                    'submitted_at'    => $now,
                ]);
            }

            // Update individual assignee status
            $this->assigneeModel->updateAssigneeStatus($taskId, $userId, $statusId);

            // Fetch target user & status details for notification
            $statusObj  = $this->statusModel->find($statusId);
            $statusName = $statusObj ? $statusObj['name'] : 'Evaluasi Selesai';

            $gradeText = ($grade !== null) ? " (Nilai: {$grade}/100)" : "";
            $feedbackText = !empty($feedback) ? ": \"{$feedback}\"" : ".";

            $notificationModel = new \App\Models\NotificationModel();
            $notificationModel->notifyUser(
                $userId,
                'Evaluasi & Nilai Tugas: ' . ($task['title'] ?? ''),
                "Pembina/BPH memberikan evaluasi langsung (Status: {$statusName}{$gradeText})" . $feedbackText,
                'task',
                'member/tasks/submit/' . $taskId
            );

            $this->auditLogModel->recordLog($evaluatorId, 'TASK_EVALUATE', "Evaluasi tugas ID {$taskId} untuk {$user['full_name']} (Nilai: " . ($grade !== null ? $grade : '-') . ")");

            $this->commitTransaction();
            return $this->success("Nilai & evaluasi untuk {$user['full_name']} berhasil disimpan.", ['submission_id' => $submissionId]);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal menyimpan evaluasi: ' . $e->getMessage());
        }
    }

    public function evaluateSubmission(int $submissionId, array $data, int $evaluatorId): array
    {
        $submission = $this->submissionModel->find($submissionId);
        if (!$submission) {
            return $this->error('Pengiriman tugas tidak ditemukan.');
        }

        return $this->evaluateAssigneeDirectly((int)$submission['task_id'], (int)$submission['user_id'], $data, $evaluatorId);
    }

    public function bulkEvaluate(int $taskId, array $userIds, array $data, int $evaluatorId): array
    {
        $task = $this->taskModel->find($taskId);
        if (!$task) {
            return $this->error('Tugas tidak ditemukan.');
        }

        $userIds = array_unique(array_filter(array_map('intval', $userIds)));
        if (empty($userIds)) {
            return $this->error('Pilih setidaknya satu anggota yang ingin dinilai.');
        }

        $grade             = (isset($data['grade']) && $data['grade'] !== '' && $data['grade'] !== null) ? max(0, min(100, (int)$data['grade'])) : null;
        $statusId          = !empty($data['status_id']) ? (int)$data['status_id'] : 5; // Default Done (5)
        $feedback          = trim($data['feedback'] ?? '');
        $overwriteExisting = !isset($data['overwrite_graded']) || !empty($data['overwrite_graded']);

        $this->beginTransaction();

        try {
            $now               = date('Y-m-d H:i:s');
            $statusObj         = $this->statusModel->find($statusId);
            $statusName        = $statusObj ? $statusObj['name'] : 'Evaluasi Selesai';
            $gradeText         = ($grade !== null) ? " (Nilai: {$grade}/100)" : "";
            $feedbackText      = !empty($feedback) ? ": \"{$feedback}\"" : ".";
            $notificationModel = new \App\Models\NotificationModel();

            $countEvaluated = 0;

            foreach ($userIds as $userId) {
                $existing = $this->submissionModel->where('task_id', $taskId)->where('user_id', $userId)->first();
                
                if ($existing) {
                    // If overwriteExisting is false and it already has a grade, skip
                    if (!$overwriteExisting && $existing['grade'] !== null) {
                        continue;
                    }

                    $this->submissionModel->update($existing['id'], [
                        'status_id'    => $statusId,
                        'grade'        => $grade,
                        'feedback'     => !empty($feedback) ? $feedback : $existing['feedback'],
                        'evaluated_by' => $evaluatorId,
                    ]);
                } else {
                    $this->submissionModel->insert([
                        'task_id'         => $taskId,
                        'user_id'         => $userId,
                        'submission_text' => !empty($feedback) ? 'Dinilai secara massal oleh Pembina / Admin' : 'Selesai / Dinilai massal oleh Admin',
                        'attachment_url'  => '',
                        'status_id'       => $statusId,
                        'grade'           => $grade,
                        'feedback'        => $feedback,
                        'evaluated_by'    => $evaluatorId,
                        'submitted_at'    => $now,
                    ]);
                }

                // Update assignee status
                $this->assigneeModel->updateAssigneeStatus($taskId, $userId, $statusId);

                // Send notification
                $notificationModel->notifyUser(
                    $userId,
                    'Evaluasi & Nilai Tugas: ' . ($task['title'] ?? ''),
                    "Pembina/BPH memberikan evaluasi (Status: {$statusName}{$gradeText})" . $feedbackText,
                    'task',
                    'member/tasks/submit/' . $taskId
                );

                $countEvaluated++;
            }

            $this->auditLogModel->recordLog($evaluatorId, 'TASK_BULK_EVALUATE', "Penilaian massal untuk {$countEvaluated} anggota pada tugas ID {$taskId} (Nilai: " . ($grade !== null ? $grade : '-') . ")");

            $this->commitTransaction();
            return $this->success("Berhasil memberikan nilai & evaluasi untuk {$countEvaluated} anggota.");
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal melakukan penilaian massal: ' . $e->getMessage());
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
