<?php

namespace App\Controllers;

use App\Models\TaskModel;
use App\Models\TaskAssigneeModel;
use App\Models\TaskSubmissionModel;
use App\Models\TaskActivityModel;
use App\Models\TaskStatusModel;
use App\Models\TaskPriorityModel;
use App\Models\LabelModel;
use App\Models\UserModel;
use App\Models\AuditLogModel;
use App\Models\NotificationModel;

class TaskController extends BaseController
{
    protected $taskModel;
    protected $taskAssigneeModel;
    protected $taskSubmissionModel;
    protected $taskActivityModel;
    protected $taskStatusModel;
    protected $taskPriorityModel;
    protected $labelModel;
    protected $userModel;
    protected $auditLogModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->taskModel           = new TaskModel();
        $this->taskAssigneeModel   = new TaskAssigneeModel();
        $this->taskSubmissionModel = new TaskSubmissionModel();
        $this->taskActivityModel   = new TaskActivityModel();
        $this->taskStatusModel     = new TaskStatusModel();
        $this->taskPriorityModel   = new TaskPriorityModel();
        $this->labelModel          = new LabelModel();
        $this->userModel           = new UserModel();
        $this->auditLogModel       = new AuditLogModel();
        $this->notificationModel   = new NotificationModel();
    }

    public function index()
    {
        $tasks      = $this->taskModel->getTasksWithDetails();
        $statuses   = $this->taskStatusModel->getOrderedStatuses();
        $priorities = $this->taskPriorityModel->getOrderedPriorities();
        $labels     = $this->labelModel->findAll();
        $members    = $this->userModel->where('status', 'active')->where('role_id', 4)->findAll();

        return view('admin/tasks/index', [
            'title'      => 'Manajemen Tugas & Proyek - Admin CMS',
            'tasks'      => $tasks,
            'statuses'   => $statuses,
            'priorities' => $priorities,
            'labels'     => $labels,
            'members'    => $members,
        ]);
    }

    public function create()
    {
        $statuses   = $this->taskStatusModel->getOrderedStatuses();
        $priorities = $this->taskPriorityModel->getOrderedPriorities();
        $labels     = $this->labelModel->findAll();
        $members    = $this->userModel->where('status', 'active')->where('role_id', 4)->findAll();

        return view('admin/tasks/create', [
            'title'      => 'Buat Tugas / Proyek Baru',
            'statuses'   => $statuses,
            'priorities' => $priorities,
            'labels'     => $labels,
            'members'    => $members,
        ]);
    }

    public function store()
    {
        $rules = [
            'title'       => 'required|min_length[3]',
            'priority_id' => 'required|integer',
            'status_id'   => 'required|integer',
            'deadline'    => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('user_id');

        $taskId = $this->taskModel->insert([
            'uuid'        => $this->taskModel->generateUuid(),
            'title'       => trim($this->request->getPost('title')),
            'description' => trim($this->request->getPost('description')),
            'priority_id' => (int) $this->request->getPost('priority_id'),
            'status_id'   => (int) $this->request->getPost('status_id'),
            'deadline'    => $this->request->getPost('deadline') ?: null,
            'created_by'  => $userId,
        ]);

        // Sync Assignees
        $assignees = $this->request->getPost('assignees') ?? [];
        if (!empty($assignees)) {
            $this->taskAssigneeModel->syncAssignees($taskId, $assignees);
        }

        // Sync Labels
        $labelIds = $this->request->getPost('labels') ?? [];
        if (!empty($labelIds)) {
            $db = \Config\Database::connect();
            foreach ($labelIds as $lid) {
                $db->table('task_labels')->insert(['task_id' => $taskId, 'label_id' => (int)$lid]);
            }
        }

        // Log ClickUp-style activity timeline
        $creatorName = session()->get('full_name');
        $this->taskActivityModel->logActivity(
            $taskId,
            $userId,
            'Task Created',
            "{$creatorName} membuat tugas baru \"{$this->request->getPost('title')}\""
        );

        // Send notifications to assignees
        foreach ($assignees as $assigneeId) {
            $this->notificationModel->notifyUser(
                (int)$assigneeId,
                'Tugas Baru Ditugaskan',
                "Anda telah ditugaskan pada tugas: {$this->request->getPost('title')}",
                'task'
            );
        }

        $this->auditLogModel->recordLog($userId, 'TASK_CREATE', "Membuat tugas ID: {$taskId}");

        return redirect()->to('/admin/tasks')->with('success', 'Tugas baru berhasil dibuat dan ditugaskan.');
    }

    public function detail($id)
    {
        $task = $this->taskModel->getTaskDetails($id);
        if (!$task) {
            return redirect()->to('/admin/tasks')->with('error', 'Tugas tidak ditemukan.');
        }

        $activities  = $this->taskActivityModel->getActivitiesForTask($id);
        $submissions = $this->taskSubmissionModel->getSubmissionsByTask($id);
        $statuses    = $this->taskStatusModel->getOrderedStatuses();
        $priorities  = $this->taskPriorityModel->getOrderedPriorities();
        $labels      = $this->labelModel->findAll();
        $members     = $this->userModel->where('status', 'active')->where('role_id', 4)->findAll();

        return view('admin/tasks/detail', [
            'title'       => 'Detail & Timeline Tugas - ' . $task['title'],
            'task'        => $task,
            'activities'  => $activities,
            'submissions' => $submissions,
            'statuses'    => $statuses,
            'priorities'  => $priorities,
            'labels'      => $labels,
            'members'     => $members,
        ]);
    }

    public function updateStatus($id)
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return redirect()->back()->with('error', 'Tugas tidak ditemukan.');
        }

        $newStatusId = (int) $this->request->getPost('status_id');
        $oldStatus   = $this->taskStatusModel->find($task['status_id']);
        $newStatus   = $this->taskStatusModel->find($newStatusId);
        $userId      = session()->get('user_id');
        $userName    = session()->get('full_name');

        $this->taskModel->update($id, ['status_id' => $newStatusId]);

        $this->taskActivityModel->logActivity(
            $id,
            $userId,
            'Status Changed',
            "{$userName} mengubah status dari '{$oldStatus['name']}' menjadi '{$newStatus['name']}'"
        );

        $this->auditLogModel->recordLog($userId, 'TASK_STATUS_UPDATE', "Mengubah status tugas ID {$id} ke {$newStatus['name']}");

        return redirect()->back()->with('success', "Status tugas berhasil diubah ke '{$newStatus['name']}'!");
    }

    public function edit($id)
    {
        $task = $this->taskModel->getTaskDetails($id);
        if (!$task) {
            return redirect()->to('/admin/tasks')->with('error', 'Tugas tidak ditemukan.');
        }

        $statuses   = $this->taskStatusModel->getOrderedStatuses();
        $priorities = $this->taskPriorityModel->getOrderedPriorities();
        $labels     = $this->labelModel->findAll();
        $members    = $this->userModel->where('status', 'active')->where('role_id', 4)->findAll();

        return view('admin/tasks/edit', [
            'title'      => 'Edit Tugas - ' . $task['title'],
            'task'       => $task,
            'statuses'   => $statuses,
            'priorities' => $priorities,
            'labels'     => $labels,
            'members'    => $members,
        ]);
    }

    public function update($id)
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return redirect()->to('/admin/tasks')->with('error', 'Tugas tidak ditemukan.');
        }

        $rules = [
            'title'       => 'required|min_length[3]',
            'priority_id' => 'required|integer',
            'status_id'   => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('user_id');

        $this->taskModel->update($id, [
            'title'       => trim($this->request->getPost('title')),
            'description' => trim($this->request->getPost('description')),
            'priority_id' => (int) $this->request->getPost('priority_id'),
            'status_id'   => (int) $this->request->getPost('status_id'),
            'deadline'    => $this->request->getPost('deadline') ?: null,
        ]);

        // Sync Assignees
        $assignees = $this->request->getPost('assignees') ?? [];
        $this->taskAssigneeModel->syncAssignees($id, $assignees);

        // Sync Labels
        $db = \Config\Database::connect();
        $db->table('task_labels')->where('task_id', $id)->delete();
        $labelIds = $this->request->getPost('labels') ?? [];
        foreach ($labelIds as $lid) {
            $db->table('task_labels')->insert(['task_id' => $id, 'label_id' => (int)$lid]);
        }

        $this->taskActivityModel->logActivity(
            $id,
            $userId,
            'Task Updated',
            session()->get('full_name') . " mengoperasikan pembaruan detail tugas"
        );

        return redirect()->to("/admin/tasks/detail/{$id}")->with('success', 'Detail tugas berhasil diperbarui.');
    }

    public function delete($id)
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return redirect()->to('/admin/tasks')->with('error', 'Tugas tidak ditemukan.');
        }

        $this->taskModel->delete($id);
        $this->auditLogModel->recordLog(session()->get('user_id'), 'TASK_DELETE', "Soft delete tugas ID: {$id}");

        return redirect()->to('/admin/tasks')->with('success', 'Tugas berhasil dihapus.');
    }

    // Member Task Portal Methods
    public function myTasks()
    {
        $userId  = session()->get('user_id');
        $myTasks = $this->taskModel->getTasksWithDetails($userId);

        foreach ($myTasks as &$task) {
            $task['my_submission'] = $this->taskSubmissionModel->getUserSubmissionForTask($task['id'], $userId);
        }

        return view('member/tasks', [
            'title'   => 'Daftar Tugas Saya - Member Portal',
            'myTasks' => $myTasks,
        ]);
    }

    public function submitTask()
    {
        $taskId         = (int) $this->request->getPost('task_id');
        $submissionText = trim($this->request->getPost('submission_text') ?? '');
        $attachmentUrl  = trim($this->request->getPost('attachment_url') ?? '');
        $userId         = session()->get('user_id');
        $userName       = session()->get('full_name');
        $now            = date('Y-m-d H:i:s');

        if (!$taskId || (empty($submissionText) && empty($attachmentUrl))) {
            return redirect()->back()->with('error', 'Isikan catatan pengumpulan atau masukkan link berkas/tautan.');
        }

        $existing = $this->taskSubmissionModel->where('task_id', $taskId)->where('user_id', $userId)->first();
        $reviewStatus = $this->taskStatusModel->where('name', 'Review')->first();
        $reviewStatusId = $reviewStatus['id'] ?? 3;

        if ($existing) {
            $this->taskSubmissionModel->update($existing['id'], [
                'submission_text' => $submissionText,
                'attachment_url'  => $attachmentUrl,
                'status_id'       => $reviewStatusId,
                'submitted_at'    => $now,
            ]);
            $actionMsg = "{$userName} memperbarui pengumpulan tugas (Resubmitted)";
        } else {
            $this->taskSubmissionModel->insert([
                'task_id'         => $taskId,
                'user_id'         => $userId,
                'submission_text' => $submissionText,
                'attachment_url'  => $attachmentUrl,
                'status_id'       => $reviewStatusId,
                'submitted_at'    => $now,
            ]);
            $actionMsg = "{$userName} mengirimkan berkas pengumpulan tugas baru";
        }

        // Update overall task status to Review
        $this->taskModel->update($taskId, ['status_id' => $reviewStatusId]);

        // Log ClickUp-style activity
        $this->taskActivityModel->logActivity($taskId, $userId, 'Submission Uploaded', $actionMsg);

        $this->auditLogModel->recordLog($userId, 'TASK_SUBMISSION', "Anggota {$userName} mengumpulkan tugas ID: {$taskId}");

        return redirect()->back()->with('success', 'Pengumpulan tugas Anda berhasil terikirim dan menunggu review BPH/Pembina.');
    }

    public function evaluateSubmission()
    {
        $submissionId = (int) $this->request->getPost('submission_id');
        $grade        = (int) $this->request->getPost('grade');
        $feedback     = trim($this->request->getPost('feedback') ?? '');
        $statusChoice = $this->request->getPost('status_choice');
        $statusIdPost = (int)$this->request->getPost('status_id');
        $evaluatorId  = session()->get('user_id');

        $submission = $this->taskSubmissionModel->find($submissionId);
        if (!$submission) {
            return redirect()->back()->with('error', 'Data pengumpulan tidak ditemukan.');
        }

        if ($statusIdPost > 0) {
            $statusId = $statusIdPost;
        } else {
            $targetStatusName = ($statusChoice === 'revision') ? 'Revision' : 'Done';
            $statusObj        = $this->taskStatusModel->where('name', $targetStatusName)->first();
            $statusId         = $statusObj['id'] ?? 5;
        }

        $this->taskSubmissionModel->update($submissionId, [
            'grade'        => $grade,
            'feedback'     => $feedback,
            'status_id'    => $statusId,
            'evaluated_by' => $evaluatorId,
        ]);

        // Update assignee status
        $this->taskAssigneeModel->updateAssigneeStatus($submission['task_id'], $submission['user_id'], $statusId);

        return redirect()->back()->with('success', 'Evaluasi tugas berhasil disimpan.');
    }
}
