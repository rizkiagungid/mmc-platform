<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\AuditLogModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $roleModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->userModel     = new UserModel();
        $this->roleModel     = new RoleModel();
        $this->auditLogModel = new AuditLogModel();
    }

    public function index()
    {
        $keyword = trim($this->request->getGet('keyword') ?? '');
        $roleId  = $this->request->getGet('role_id') ? (int)$this->request->getGet('role_id') : null;

        $users = $this->userModel->getUsersWithRole($roleId, $keyword);
        $roles = $this->roleModel->findAll();

        return view('admin/users/index', [
            'title'   => 'Manajemen Pengguna & Anggota - Admin CMS',
            'users'   => $users,
            'roles'   => $roles,
            'keyword' => $keyword,
            'roleId'  => $roleId,
        ]);
    }

    public function create()
    {
        $roles = $this->roleModel->findAll();
        return view('admin/users/create', [
            'title' => 'Tambah Anggota / Pengguna Baru',
            'roles' => $roles,
        ]);
    }

    public function store()
    {
        $rules = [
            'role_id'    => 'required|integer',
            'full_name'  => 'required|min_length[3]',
            'username'   => 'required|alpha_numeric_punct|is_unique[users.username]',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'password'   => 'required|min_length[6]',
            'nis_nip'    => 'permit_empty',
            'class_dept' => 'permit_empty',
            'phone'      => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = $this->userModel->insert([
            'member_uuid'   => $this->userModel->generateUuid(),
            'role_id'       => (int) $this->request->getPost('role_id'),
            'username'      => trim($this->request->getPost('username')),
            'email'         => trim($this->request->getPost('email')),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'full_name'     => trim($this->request->getPost('full_name')),
            'nis_nip'       => trim($this->request->getPost('nis_nip')),
            'class_dept'    => trim($this->request->getPost('class_dept')),
            'phone'         => trim($this->request->getPost('phone')),
            'qr_version'    => 1,
            'qr_updated_at' => date('Y-m-d H:i:s'),
            'status'        => $this->request->getPost('status') ?? 'active',
        ]);

        $this->auditLogModel->recordLog(session()->get('user_id'), 'USER_CREATE', "Membuat pengguna baru: {$this->request->getPost('full_name')}");

        return redirect()->to('/admin/users')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        $roles = $this->roleModel->findAll();
        return view('admin/users/edit', [
            'title' => 'Edit Pengguna - ' . $user['full_name'],
            'user'  => $user,
            'roles' => $roles,
        ]);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        $rules = [
            'role_id'    => 'required|integer',
            'full_name'  => 'required|min_length[3]',
            'email'      => "required|valid_email|is_unique[users.email,id,{$id}]",
            'username'   => "required|alpha_numeric_punct|is_unique[users.username,id,{$id}]",
            'status'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'role_id'    => (int) $this->request->getPost('role_id'),
            'username'   => trim($this->request->getPost('username')),
            'email'      => trim($this->request->getPost('email')),
            'full_name'  => trim($this->request->getPost('full_name')),
            'nis_nip'    => trim($this->request->getPost('nis_nip')),
            'class_dept' => trim($this->request->getPost('class_dept')),
            'phone'      => trim($this->request->getPost('phone')),
            'status'     => $this->request->getPost('status'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->userModel->update($id, $updateData);
        $this->auditLogModel->recordLog(session()->get('user_id'), 'USER_UPDATE', "Mengubah data pengguna ID: {$id}");

        return redirect()->to('/admin/users')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        $this->userModel->delete($id);
        $this->auditLogModel->recordLog(session()->get('user_id'), 'USER_DELETE', "Soft delete pengguna ID: {$id} ({$user['full_name']})");

        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function regenerateQr($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        $this->userModel->regenerateQrCode($id);
        $this->auditLogModel->recordLog(session()->get('user_id'), 'QR_REGENERATE', "Meregenerasi QR Code permanen untuk anggota: {$user['full_name']}");

        return redirect()->back()->with('success', "QR Code untuk {$user['full_name']} berhasil diperbarui/diregenerasi!");
    }

    public function showQr($id)
    {
        $user = $this->userModel->getUserByUuid($id) ?? $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        return view('admin/users/qr_card', [
            'title' => 'ID Card & Member QR - ' . $user['full_name'],
            'user'  => $user,
        ]);
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        $user   = $this->userModel->getUserByUuid(session()->get('member_uuid')) ?? $this->userModel->find($userId);

        return view('member/profile', [
            'title' => 'Profil Saya - Multimedia Club',
            'user'  => $user,
        ]);
    }

    public function updateProfile()
    {
        $userId = session()->get('user_id');

        $rules = [
            'full_name'  => 'required|min_length[3]',
            'email'      => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'phone'      => 'permit_empty',
            'class_dept' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'full_name'  => trim($this->request->getPost('full_name')),
            'email'      => trim($this->request->getPost('email')),
            'phone'      => trim($this->request->getPost('phone')),
            'class_dept' => trim($this->request->getPost('class_dept')),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->userModel->update($userId, $updateData);
        session()->set('full_name', $updateData['full_name']);
        session()->set('email', $updateData['email']);

        $this->auditLogModel->recordLog($userId, 'PROFILE_UPDATE', 'Pengguna memperbarui data profil mandiri');

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
