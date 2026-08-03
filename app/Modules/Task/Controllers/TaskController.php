<?php

namespace App\Modules\Task\Controllers;

use App\Controllers\BaseController;
use App\Modules\Task\Services\TaskService;

class TaskController extends BaseController
{
    protected $taskService;

    public function __construct()
    {
        $this->taskService = new TaskService();
    }

    public function index()
    {
        $filters = [
            'keyword'         => trim($this->request->getGet('keyword') ?? ''),
            'priority_id'     => $this->request->getGet('priority_id') ? (int)$this->request->getGet('priority_id') : null,
            'status_id'       => $this->request->getGet('status_id') ? (int)$this->request->getGet('status_id') : null,
            'deadline_filter' => trim($this->request->getGet('deadline_filter') ?? ''),
        ];

        $tasks      = $this->taskService->getAllTasks(null, $filters);
        $statuses   = $this->taskService->getAllStatuses();
        $priorities = $this->taskService->getAllPriorities();

        return view('App\Modules\Task\Views\index', [
            'title'      => 'Manajemen Tugas & Proyek MMC',
            'tasks'      => $tasks,
            'statuses'   => $statuses,
            'priorities' => $priorities,
            'filters'    => $filters,
        ]);
    }

    public function create()
    {
        return view('App\Modules\Task\Views\create', [
            'title'      => 'Buat Tugas Baru',
            'members'    => $this->taskService->getAllMembers(),
            'priorities' => $this->taskService->getAllPriorities(),
            'statuses'   => $this->taskService->getAllStatuses(),
        ]);
    }

    public function store()
    {
        $result = $this->taskService->createTask($this->request->getPost(), session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->to('/admin/tasks')->with('success', $result['body']['message']);
    }

    public function edit(int $id)
    {
        $task = $this->taskService->getTaskDetails($id);
        if (!$task) {
            return redirect()->to('/admin/tasks')->with('error', 'Tugas tidak ditemukan.');
        }

        return view('App\Modules\Task\Views\edit', [
            'title'      => 'Edit Tugas: ' . $task['title'],
            'task'       => $task,
            'members'    => $this->taskService->getAllMembers(),
            'priorities' => $this->taskService->getAllPriorities(),
            'statuses'   => $this->taskService->getAllStatuses(),
        ]);
    }

    public function update(int $id)
    {
        $result = $this->taskService->updateTask($id, $this->request->getPost(), session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->to('/admin/tasks')->with('success', $result['body']['message']);
    }

    public function delete(int $id)
    {
        $result = $this->taskService->deleteTask($id, session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->to('/admin/tasks')->with('error', $result['body']['message']);
        }

        return redirect()->to('/admin/tasks')->with('success', $result['body']['message']);
    }

    public function detail(int $id)
    {
        $task = $this->taskService->getTaskDetails($id);
        if (!$task) {
            return redirect()->to('/admin/tasks')->with('error', 'Tugas tidak ditemukan.');
        }

        $submissions = $this->taskService->getSubmissionsByTask($id);
        $statuses    = $this->taskService->getAllStatuses();
        $comments    = $this->taskService->getTaskComments($id);
        $allUsers    = $this->taskService->getAllMembers();

        return view('App\Modules\Task\Views\detail', [
            'title'       => 'Peninjauan Tugas: ' . $task['title'],
            'task'        => $task,
            'submissions' => $submissions,
            'statuses'    => $statuses,
            'comments'    => $comments,
            'allUsers'    => $allUsers,
        ]);
    }

    public function evaluate(int $submissionId)
    {
        $result = $this->taskService->evaluateSubmission($submissionId, $this->request->getPost(), session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }

    public function myTasks()
    {
        $userId  = session()->get('user_id');
        $filters = [
            'keyword'         => $this->request->getGet('keyword'),
            'priority_id'     => $this->request->getGet('priority_id'),
            'status_id'       => $this->request->getGet('status_id'),
            'deadline_filter' => $this->request->getGet('deadline_filter'),
        ];

        $tasks      = $this->taskService->getAllTasks($userId, $filters);
        $priorities = $this->taskService->getAllPriorities();
        $statuses   = $this->taskService->getAllStatuses();

        return view('App\Modules\Task\Views\my_tasks', [
            'title'          => 'Tugas Saya - Portal Anggota',
            'tasks'          => $tasks,
            'priorities'     => $priorities,
            'statuses'       => $statuses,
            'selectedFilter' => $filters,
        ]);
    }

    public function submitForm(int $taskId)
    {
        $userId = session()->get('user_id');
        $task   = $this->taskService->getTaskDetails($taskId);

        if (!$task) {
            return redirect()->to('/member/tasks')->with('error', 'Tugas tidak ditemukan.');
        }

        $submission = $this->taskService->getUserSubmissionForTask($taskId, $userId);
        $statuses   = $this->taskService->getAllStatuses();
        $comments   = $this->taskService->getTaskComments($taskId);
        $allUsers   = $this->taskService->getAllMembers();

        $myStatusId    = 1;
        $myStatusName  = 'Todo';
        $myStatusColor = '#3b82f6';
        if (!empty($task['assignees'])) {
            foreach ($task['assignees'] as $a) {
                if ($a['id'] == $userId) {
                    $myStatusId    = $a['status_id'] ?? 1;
                    $myStatusName  = $a['status_name'] ?? 'Todo';
                    $myStatusColor = $a['status_color'] ?? '#3b82f6';
                    break;
                }
            }
        }

        return view('App\Modules\Task\Views\submit', [
            'title'         => 'Kirim Tugas: ' . $task['title'],
            'task'          => $task,
            'submission'    => $submission,
            'statuses'      => $statuses,
            'myStatusId'    => $myStatusId,
            'myStatusName'  => $myStatusName,
            'myStatusColor' => $myStatusColor,
            'comments'      => $comments,
            'allUsers'      => $allUsers,
        ]);
    }

    public function submitStore(int $taskId)
    {
        $userId = session()->get('user_id');
        $file   = $this->request->getFile('attachment_file');

        $result = $this->taskService->submitTask($taskId, $userId, $this->request->getPost(), $file);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->to('/member/tasks')->with('success', $result['body']['message']);
    }

    public function quickUpdateStatus(int $taskId)
    {
        $statusId = (int)$this->request->getPost('status_id');
        if ($statusId > 0) {
            $db = \Config\Database::connect();
            $db->table('tasks')->where('id', $taskId)->update([
                'status_id'  => $statusId,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $actorId = session()->get('user_id');
            $auditLogModel = new \App\Models\AuditLogModel();
            $auditLogModel->recordLog($actorId, 'TASK_STATUS_UPDATE', "Perbarui status utama tugas ID {$taskId} ke status ID {$statusId}");

            return redirect()->to('/admin/tasks')->with('success', 'Status utama tugas berhasil diperbarui secara langsung.');
        }
        return redirect()->to('/admin/tasks')->with('error', 'Status tidak valid.');
    }

    public function quickUpdateAssigneeStatus(int $taskId)
    {
        $userId   = (int)$this->request->getPost('user_id');
        $statusId = (int)$this->request->getPost('status_id');

        if ($userId > 0 && $statusId > 0) {
            $assigneeModel = new \App\Models\TaskAssigneeModel();
            $assigneeModel->updateAssigneeStatus($taskId, $userId, $statusId);

            $actorId = session()->get('user_id');
            $auditLogModel = new \App\Models\AuditLogModel();
            $auditLogModel->recordLog($actorId, 'TASK_ASSIGNEE_STATUS_UPDATE', "Perbarui status assignee user ID {$userId} pada tugas ID {$taskId}");

            return redirect()->to('/admin/tasks')->with('success', 'Status anggota assignee berhasil diperbarui secara langsung.');
        }
        return redirect()->to('/admin/tasks')->with('error', 'Gagal memperbarui status anggota.');
    }

    public function quickUpdatePriority(int $taskId)
    {
        $priorityId = (int)$this->request->getPost('priority_id');
        if ($priorityId > 0) {
            $db = \Config\Database::connect();
            $db->table('tasks')->where('id', $taskId)->update([
                'priority_id' => $priorityId,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);

            $actorId = session()->get('user_id');
            $auditLogModel = new \App\Models\AuditLogModel();
            $auditLogModel->recordLog($actorId, 'TASK_PRIORITY_UPDATE', "Perbarui prioritas tugas ID {$taskId} ke prioritas ID {$priorityId}");

            return redirect()->to('/admin/tasks')->with('success', 'Prioritas tugas berhasil diperbarui secara langsung.');
        }
        return redirect()->to('/admin/tasks')->with('error', 'Prioritas tidak valid.');
    }

    public function postComment(int $taskId)
    {
        $userId  = session()->get('user_id');
        $comment = $this->request->getPost('comment');

        $result = $this->taskService->addComment($taskId, $userId, (string)$comment);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', 'Komentar / pesan diskusi berhasil dikirim.');
    }
}
