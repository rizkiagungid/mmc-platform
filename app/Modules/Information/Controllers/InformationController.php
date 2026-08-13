<?php

namespace App\Modules\Information\Controllers;

use App\Controllers\BaseController;
use App\Models\InformationModel;
use App\Models\InformationReadModel;

class InformationController extends BaseController
{
    protected $infoModel;
    protected $readModel;

    public function __construct()
    {
        $this->infoModel = new InformationModel();
        $this->readModel = new InformationReadModel();
    }

    /**
     * Member View: Main Information List Page (/informasi)
     */
    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $category = (string)($this->request->getGet('category') ?? 'all');
        $validCategories = [
            'all',
            'Informasi Ekskul',
            'Informasi Sekolah',
            'Divisi Programming',
            'Divisi Broadcasting',
            'Informasi Maintenance'
        ];

        if (!in_array($category, $validCategories, true)) {
            $category = 'all';
        }

        $informations = $this->infoModel->getInformationsForMember($userId, $category);

        $data = [
            'title'          => 'Pusat Informasi MMC',
            'informations'  => $informations,
            'activeCategory' => $category,
        ];

        return view('App\Modules\Information\Views\index', $data);
    }

    /**
     * Member Action: Mark Information as Read (AJAX POST)
     */
    public function markAsRead(int $id)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $info = $this->infoModel->find($id);
        if (!$info) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Informasi tidak ditemukan']);
        }

        $success = $this->readModel->markAsRead($id, $userId);

        if ($success) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Informasi telah ditandai sudah dibaca.',
                'info_id' => $id
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui status baca.']);
    }

    /**
     * Admin View: Information Management Datatable / List (/admin/informasi)
     */
    public function adminIndex()
    {
        $informations = $this->infoModel->getAdminInformations();

        $data = [
            'title'        => 'Kelola Informasi Klub MMC',
            'informations' => $informations,
        ];

        return view('App\Modules\Information\Views\admin\index', $data);
    }

    /**
     * Admin View: Form Create Information (/admin/informasi/create)
     */
    public function create()
    {
        $data = [
            'title' => 'Buat Informasi Baru',
            'categories' => [
                'Informasi Ekskul',
                'Informasi Sekolah',
                'Divisi Programming',
                'Divisi Broadcasting',
                'Informasi Maintenance'
            ],
        ];

        return view('App\Modules\Information\Views\admin\create', $data);
    }

    /**
     * Admin Action: Store Information (/admin/informasi/store)
     */
    public function store()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $rules = [
            'title'       => 'required|min_length[3]|max_length[255]',
            'category'    => 'required',
            'description' => 'required',
            'date_time'   => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi semua bidang yang wajib diisi.');
        }

        $data = [
            'title'       => $this->request->getPost('title'),
            'category'    => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'date_time'   => date('Y-m-d H:i:s', strtotime($this->request->getPost('date_time'))),
            'created_by'  => $userId,
            'is_popup'    => $this->request->getPost('is_popup') ? 1 : 0,
            'status'      => $this->request->getPost('status') === 'archived' ? 'archived' : 'active',
        ];

        $infoId = $this->infoModel->insert($data);

        // Notify all active users about new Information
        try {
            $userModel = new \App\Models\UserModel();
            $activeUsers = $userModel->where('status', 'active')->select('id')->findAll();
            $userIds = array_column($activeUsers, 'id');

            if (!empty($userIds)) {
                $notifModel = new \App\Models\NotificationModel();
                $notifModel->notifyUsers(
                    $userIds,
                    '📢 Informasi Baru: ' . $data['title'],
                    'Ada pengumuman terbaru (' . $data['category'] . '). Klik untuk membaca selengkapnya.',
                    'information',
                    base_url('informasi')
                );
            }
        } catch (\Throwable $e) {
            log_message('error', 'Failed to send information notification: ' . $e->getMessage());
        }

        // Record audit log if function exists
        if (function_exists('log_audit')) {
            log_audit('CREATE_INFORMATION', 'Membuat informasi baru: ' . $data['title']);
        }

        return redirect()->to('/admin/informasi')->with('success', 'Informasi baru berhasil dibuat dan dipublikasikan!');
    }

    /**
     * Admin View: Form Edit Information (/admin/informasi/edit/$1)
     */
    public function edit(int $id)
    {
        $info = $this->infoModel->find($id);
        if (!$info) {
            return redirect()->to('/admin/informasi')->with('error', 'Informasi tidak ditemukan.');
        }

        $data = [
            'title'       => 'Edit Informasi',
            'info'        => $info,
            'categories'  => [
                'Informasi Ekskul',
                'Informasi Sekolah',
                'Divisi Programming',
                'Divisi Broadcasting',
                'Informasi Maintenance'
            ],
        ];

        return view('App\Modules\Information\Views\admin\edit', $data);
    }

    /**
     * Admin Action: Update Information (/admin/informasi/update/$1)
     */
    public function update(int $id)
    {
        $info = $this->infoModel->find($id);
        if (!$info) {
            return redirect()->to('/admin/informasi')->with('error', 'Informasi tidak ditemukan.');
        }

        $rules = [
            'title'       => 'required|min_length[3]|max_length[255]',
            'category'    => 'required',
            'description' => 'required',
            'date_time'   => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Mohon lengkapi semua bidang data.');
        }

        $data = [
            'title'       => $this->request->getPost('title'),
            'category'    => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'date_time'   => date('Y-m-d H:i:s', strtotime($this->request->getPost('date_time'))),
            'is_popup'    => $this->request->getPost('is_popup') ? 1 : 0,
            'status'      => $this->request->getPost('status') === 'archived' ? 'archived' : 'active',
        ];

        $this->infoModel->update($id, $data);

        if (function_exists('log_audit')) {
            log_audit('UPDATE_INFORMATION', 'Perbarui informasi ID: ' . $id);
        }

        return redirect()->to('/admin/informasi')->with('success', 'Informasi berhasil diperbarui!');
    }

    /**
     * Admin Action: Delete Information (/admin/informasi/delete/$1)
     */
    public function delete(int $id)
    {
        $info = $this->infoModel->find($id);
        if ($info) {
            $this->infoModel->delete($id);
            if (function_exists('log_audit')) {
                log_audit('DELETE_INFORMATION', 'Menghapus informasi ID: ' . $id);
            }
            return redirect()->to('/admin/informasi')->with('success', 'Informasi berhasil dihapus.');
        }

        return redirect()->to('/admin/informasi')->with('error', 'Informasi tidak ditemukan.');
    }

    /**
     * Admin Action: Quick Toggle Popup Status (AJAX POST)
     */
    public function togglePopup(int $id)
    {
        $info = $this->infoModel->find($id);
        if (!$info) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Informasi tidak ditemukan']);
        }

        $newVal = ($info['is_popup'] == 1) ? 0 : 1;
        $this->infoModel->update($id, ['is_popup' => $newVal]);

        return $this->response->setJSON([
            'status'   => 'success',
            'is_popup' => $newVal,
            'message'  => $newVal ? 'Popup diaktifkan untuk informasi ini' : 'Popup dinonaktifkan'
        ]);
    }
}
