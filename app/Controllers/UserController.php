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
        $keyword    = trim($this->request->getGet('keyword') ?? '');
        $roleId     = $this->request->getGet('role_id') ? (int)$this->request->getGet('role_id') : null;
        $classGrade = trim($this->request->getGet('class_grade') ?? '');
        $classRoom  = trim($this->request->getGet('class_room') ?? '');
        $division   = trim($this->request->getGet('division') ?? '');
        $status     = trim($this->request->getGet('status') ?? '');
        $hasAvatar  = $this->request->getGet('has_avatar');

        $filters = [
            'class_grade' => $classGrade,
            'class_room'  => $classRoom,
            'division'    => $division,
            'status'      => $status,
            'has_avatar'  => $hasAvatar,
        ];

        $users = $this->userModel->getUsersWithRole($roleId, $keyword, true, $filters);
        $roles = $this->roleModel->findAll();

        return view('admin/users/index', [
            'title'      => 'Manajemen Pengguna & Anggota - Admin CMS',
            'users'      => $users,
            'roles'      => $roles,
            'keyword'    => $keyword,
            'roleId'     => $roleId,
            'classGrade' => $classGrade,
            'classRoom'  => $classRoom,
            'division'   => $division,
            'status'     => $status,
            'hasAvatar'  => $hasAvatar,
        ]);
    }

    public function exportCsv()
    {
        $scope     = $this->request->getPost('export_scope') ?? 'all';
        $columns   = $this->request->getPost('columns') ?? [];

        $keyword    = trim($this->request->getPost('keyword') ?? $this->request->getGet('keyword') ?? '');
        $roleId     = $this->request->getPost('role_id') ? (int)$this->request->getPost('role_id') : ($this->request->getGet('role_id') ? (int)$this->request->getGet('role_id') : null);
        $classGrade = trim($this->request->getPost('class_grade') ?? $this->request->getGet('class_grade') ?? '');
        $classRoom  = trim($this->request->getPost('class_room') ?? $this->request->getGet('class_room') ?? '');
        $division   = trim($this->request->getPost('division') ?? $this->request->getGet('division') ?? '');
        $status     = trim($this->request->getPost('status') ?? $this->request->getGet('status') ?? '');
        $hasAvatar  = $this->request->getPost('has_avatar') ?? $this->request->getGet('has_avatar');

        $filters = [];
        if ($scope === 'filtered') {
            $filters = [
                'class_grade' => $classGrade,
                'class_room'  => $classRoom,
                'division'    => $division,
                'status'      => $status,
                'has_avatar'  => $hasAvatar,
            ];
        }

        $users = $this->userModel->getUsersWithRole(($scope === 'filtered') ? $roleId : null, ($scope === 'filtered') ? $keyword : null, true, $filters);

        $availableColumns = [
            'id'               => 'ID User',
            'member_uuid'      => 'Member UUID',
            'full_name'        => 'Nama Lengkap',
            'username'         => 'Username',
            'email'            => 'Email',
            'nis_nip'          => 'NIS / NIP',
            'phone'            => 'No HP / WhatsApp',
            'class_dept'       => 'Kelas & Divisi',
            'role_name'        => 'Role',
            'status'           => 'Status',
            'birth_date'       => 'Tanggal Lahir',
            'address'          => 'Alamat Lengkap',
            'social_instagram' => 'Instagram',
            'social_tiktok'    => 'TikTok',
            'social_facebook'  => 'Facebook',
            'social_linkedin'  => 'LinkedIn',
            'social_github'    => 'GitHub',
            'qr_version'       => 'Versi QR Code',
            'created_at'       => 'Tanggal Terdaftar',
        ];

        if (empty($columns) || !is_array($columns)) {
            $columns = array_keys($availableColumns);
        }

        $selectedHeaders = [];
        foreach ($columns as $colKey) {
            if (isset($availableColumns[$colKey])) {
                $selectedHeaders[] = $availableColumns[$colKey];
            }
        }

        $filename = 'Data_Anggota_MMC_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // Write UTF-8 BOM for Microsoft Excel compatibility
        fprintf($output, "\xEF\xBB\xBF");

        // Write CSV Header
        fputcsv($output, $selectedHeaders);

        // Write Data Rows
        foreach ($users as $u) {
            $row = [];
            foreach ($columns as $colKey) {
                if ($colKey === 'status') {
                    $st = $u['status'] ?? '';
                    if ($st === 'active') $val = 'Aktif';
                    elseif ($st === 'inactive') $val = 'Menunggu Konfirmasi';
                    elseif ($st === 'suspended') $val = 'Ditangguhkan (Suspended)';
                    elseif ($st === 'left') $val = 'Keluar Ekskul';
                    else $val = strtoupper($st);
                    $row[] = $val;
                } else {
                    $row[] = $u[$colKey] ?? '';
                }
            }
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
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
            'role_id'          => 'required|integer',
            'full_name'        => 'required|min_length[3]',
            'username'         => 'required|alpha_numeric_punct|is_unique[users.username]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'nis_nip'          => 'permit_empty',
            'class_dept'       => 'permit_empty',
            'phone'            => 'permit_empty',
            'address'          => 'permit_empty',
            'birth_date'       => 'permit_empty',
            'social_instagram' => 'permit_empty',
            'social_tiktok'    => 'permit_empty',
            'social_facebook'  => 'permit_empty',
            'social_linkedin'  => 'permit_empty',
            'social_github'    => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = $this->userModel->insert([
            'member_uuid'      => $this->userModel->generateUuid(),
            'role_id'          => (int) $this->request->getPost('role_id'),
            'username'         => trim($this->request->getPost('username')),
            'email'            => trim($this->request->getPost('email')),
            'password_hash'    => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'full_name'        => trim($this->request->getPost('full_name')),
            'nis_nip'          => trim($this->request->getPost('nis_nip')),
            'class_dept'       => $this->resolveClassDeptPost(),
            'phone'            => trim($this->request->getPost('phone')),
            'address'          => trim($this->request->getPost('address') ?? '') ?: null,
            'birth_date'       => !empty($this->request->getPost('birth_date')) ? $this->request->getPost('birth_date') : null,
            'social_instagram' => trim($this->request->getPost('social_instagram') ?? '') ?: null,
            'social_tiktok'    => trim($this->request->getPost('social_tiktok') ?? '') ?: null,
            'social_facebook'  => trim($this->request->getPost('social_facebook') ?? '') ?: null,
            'social_linkedin'  => trim($this->request->getPost('social_linkedin') ?? '') ?: null,
            'social_github'    => trim($this->request->getPost('social_github') ?? '') ?: null,
            'qr_version'       => 1,
            'qr_updated_at'    => date('Y-m-d H:i:s'),
            'status'           => $this->request->getPost('status') ?? 'active',
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

        $emailInput    = trim((string)$this->request->getPost('email'));
        $usernameInput = trim((string)$this->request->getPost('username'));

        $db = \Config\Database::connect();

        // Unique email check ignoring current user & soft-deleted users
        $emailExists = $db->table('users')
                          ->where('email', $emailInput)
                          ->where('id !=', $id)
                          ->where('deleted_at IS NULL')
                          ->countAllResults();

        if ($emailExists > 0) {
            return redirect()->back()->withInput()->with('error', 'Email "' . esc($emailInput) . '" sudah digunakan oleh akun anggota/pengguna lain.');
        }

        // Unique username check ignoring current user & soft-deleted users
        $usernameExists = $db->table('users')
                             ->where('username', $usernameInput)
                             ->where('id !=', $id)
                             ->where('deleted_at IS NULL')
                             ->countAllResults();

        if ($usernameExists > 0) {
            return redirect()->back()->withInput()->with('error', 'Username "' . esc($usernameInput) . '" sudah digunakan oleh akun anggota/pengguna lain.');
        }

        $rules = [
            'role_id'          => 'required|integer',
            'full_name'        => 'required|min_length[3]',
            'email'            => 'required|valid_email',
            'username'         => 'required|alpha_numeric_punct',
            'status'           => 'required',
            'nis_nip'          => 'permit_empty',
            'phone'            => 'permit_empty',
            'address'          => 'permit_empty',
            'birth_date'       => 'permit_empty',
            'social_instagram' => 'permit_empty',
            'social_tiktok'    => 'permit_empty',
            'social_facebook'  => 'permit_empty',
            'social_linkedin'  => 'permit_empty',
            'social_github'    => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'role_id'          => (int) $this->request->getPost('role_id'),
            'username'         => trim($this->request->getPost('username')),
            'email'            => trim($this->request->getPost('email')),
            'full_name'        => trim($this->request->getPost('full_name')),
            'nis_nip'          => trim($this->request->getPost('nis_nip')),
            'class_dept'       => $this->resolveClassDeptPost(),
            'phone'            => trim($this->request->getPost('phone')),
            'address'          => trim($this->request->getPost('address') ?? '') ?: null,
            'birth_date'       => !empty($this->request->getPost('birth_date')) ? $this->request->getPost('birth_date') : null,
            'social_instagram' => trim($this->request->getPost('social_instagram') ?? '') ?: null,
            'social_tiktok'    => trim($this->request->getPost('social_tiktok') ?? '') ?: null,
            'social_facebook'  => trim($this->request->getPost('social_facebook') ?? '') ?: null,
            'social_linkedin'  => trim($this->request->getPost('social_linkedin') ?? '') ?: null,
            'social_github'    => trim($this->request->getPost('social_github') ?? '') ?: null,
            'status'           => $this->request->getPost('status'),
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
        $userId             = session()->get('user_id');
        $roleSlug           = session()->get('role_slug');
        $canEditUsernameNis = in_array($roleSlug, ['superadmin', 'pembina', 'bph']);

        $rules = [
            'full_name'        => 'required|min_length[3]',
            'email'            => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'phone'            => 'permit_empty',
            'class_dept'       => 'permit_empty',
            'address'          => 'permit_empty',
            'username'         => "required|alpha_numeric_punct|is_unique[users.username,id,{$userId}]",
            'nis_nip'          => 'permit_empty',
            'birth_date'       => 'permit_empty',
            'social_instagram' => 'permit_empty',
            'social_tiktok'    => 'permit_empty',
            'social_facebook'  => 'permit_empty',
            'social_linkedin'  => 'permit_empty',
            'social_github'    => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $this->userModel->find($userId);

        $updateData = [
            'full_name'        => trim($this->request->getPost('full_name')),
            'email'            => trim($this->request->getPost('email')),
            'phone'            => trim($this->request->getPost('phone')),
            'class_dept'       => $this->resolveClassDeptPost(),
            'address'          => trim($this->request->getPost('address') ?? '') ?: null,
            'birth_date'       => !empty($this->request->getPost('birth_date')) ? $this->request->getPost('birth_date') : null,
            'social_instagram' => trim($this->request->getPost('social_instagram') ?? '') ?: null,
            'social_tiktok'    => trim($this->request->getPost('social_tiktok') ?? '') ?: null,
            'social_facebook'  => trim($this->request->getPost('social_facebook') ?? '') ?: null,
            'social_linkedin'  => trim($this->request->getPost('social_linkedin') ?? '') ?: null,
            'social_github'    => trim($this->request->getPost('social_github') ?? '') ?: null,
        ];

        $changes = [];

        $newUsername = trim($this->request->getPost('username') ?? '');
        if (!empty($newUsername) && $newUsername !== $user['username']) {
            $updateData['username'] = $newUsername;
            $changes[] = "username dari '@{$user['username']}' ke '@{$newUsername}'";
        }

        $newNisNip = trim($this->request->getPost('nis_nip') ?? '') ?: null;
        if ($newNisNip !== ($user['nis_nip'] ?? null)) {
            $updateData['nis_nip'] = $newNisNip;
            $changes[] = "NIS/NIP";
        }

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
            $changes[] = "kata sandi";
        }

        if ($updateData['full_name'] !== $user['full_name']) {
            $changes[] = "nama lengkap";
        }

        $this->userModel->update($userId, $updateData);
        session()->set('full_name', $updateData['full_name']);
        session()->set('email', $updateData['email']);
        if (isset($updateData['username'])) {
            session()->set('username', $updateData['username']);
        }

        $logDetail = !empty($changes) ? implode(', ', $changes) : 'data profil';
        $this->auditLogModel->recordLog($userId, 'PROFILE_UPDATE', "Pengguna mandiri memperbarui data profil ({$logDetail})");

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    private function resolveClassDeptPost(): string
    {
        $grade = trim($this->request->getPost('class_grade') ?? '');
        $room  = trim($this->request->getPost('class_room') ?? '');
        $div   = trim($this->request->getPost('division') ?? '');

        if (!empty($grade) && !empty($room) && !empty($div)) {
            return "{$grade} {$room} - {$div}";
        }
        return trim($this->request->getPost('class_dept') ?? '');
    }
}
