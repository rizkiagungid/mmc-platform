<?php

namespace App\Modules\User\Controllers;

use App\Controllers\BaseController;
use App\Modules\User\Services\UserService;

class UserController extends BaseController
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    private function canModifyUser($userIds): bool
    {
        if (session()->get('role_slug') !== 'bph') {
            return true;
        }

        $userIds = (array)$userIds;
        if (empty($userIds)) return true;

        $restrictedUsersCount = \Config\Database::connect()->table('users')
            ->whereIn('id', $userIds)
            ->whereIn('role_id', [1, 2])
            ->countAllResults();

        return $restrictedUsersCount === 0;
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

        $users = $this->userService->getAllUsers($roleId, $keyword, $filters);
        $roles = $this->userService->getAllRoles();
        $stats = $this->userService->getMemberStats();

        return view('App\Modules\User\Views\index', [
            'title'      => 'Manajemen Pengguna & Anggota - Admin CMS',
            'users'      => $users,
            'roles'      => $roles,
            'stats'      => $stats,
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

        $users = $this->userService->getAllUsers(($scope === 'filtered') ? $roleId : null, ($scope === 'filtered') ? $keyword : null, $filters);

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
        $roles = $this->userService->getAllRoles();
        return view('App\Modules\User\Views\create', [
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

        if (session()->get('role_slug') !== 'superadmin' && (int)$this->request->getPost('role_id') === 1) {
            return redirect()->back()->withInput()->with('error', 'Anda tidak memiliki hak akses untuk memberikan role Super Admin.');
        }

        if (session()->get('role_slug') === 'bph' && (int)$this->request->getPost('role_id') === 2) {
            return redirect()->back()->withInput()->with('error', 'Anda tidak memiliki hak akses untuk memberikan role Pembina.');
        }

        $postData = $this->request->getPost();
        if (!empty($postData['class_grade']) && !empty($postData['class_room']) && !empty($postData['division'])) {
            $postData['class_dept'] = trim($postData['class_grade']) . ' ' . trim($postData['class_room']) . ' - ' . trim($postData['division']);
        }

        $result = $this->userService->createUser($postData, session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->to('/admin/users')->with('success', $result['body']['message']);
    }

    public function edit($id)
    {
        $user = $this->userService->getUserById((int)$id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        if (!$this->canModifyUser($id)) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak memiliki hak akses untuk mengedit pengguna dengan role Super Admin atau Pembina.');
        }

        $roles = $this->userService->getAllRoles();
        return view('App\Modules\User\Views\edit', [
            'title' => 'Edit Pengguna - ' . $user['full_name'],
            'user'  => $user,
            'roles' => $roles,
        ]);
    }

    public function update($id)
    {
        $user = $this->userService->getUserById((int)$id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        if (!$this->canModifyUser($id)) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak memiliki hak akses untuk mengubah pengguna dengan role Super Admin atau Pembina.');
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

        if (session()->get('role_slug') !== 'superadmin' && (int)$this->request->getPost('role_id') === 1) {
            return redirect()->back()->withInput()->with('error', 'Anda tidak memiliki hak akses untuk memberikan role Super Admin.');
        }

        if (session()->get('role_slug') === 'bph' && (int)$this->request->getPost('role_id') === 2) {
            return redirect()->back()->withInput()->with('error', 'Anda tidak memiliki hak akses untuk memberikan role Pembina.');
        }

        $postData = $this->request->getPost();
        if (!empty($postData['class_grade']) && !empty($postData['class_room']) && !empty($postData['division'])) {
            $postData['class_dept'] = trim($postData['class_grade']) . ' ' . trim($postData['class_room']) . ' - ' . trim($postData['division']);
        }

        $result = $this->userService->updateUser((int)$id, $postData, session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->to('/admin/users')->with('success', $result['body']['message']);
    }

    public function delete($id)
    {
        if (!$this->canModifyUser($id)) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak memiliki hak akses untuk menghapus pengguna dengan role Super Admin atau Pembina.');
        }

        $result = $this->userService->deleteUser((int)$id, session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->to('/admin/users')->with('error', $result['body']['message']);
        }

        return redirect()->to('/admin/users')->with('success', $result['body']['message']);
    }

    public function activate($id)
    {
        if (!$this->canModifyUser($id)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk mengubah pengguna dengan role Super Admin atau Pembina.');
        }

        $result = $this->userService->activateUser((int)$id, session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }

    public function regenerateQr($id)
    {
        $result = $this->userService->regenerateMemberQr((int)$id, session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }

    public function showQr($uuidOrId)
    {
        $user = $this->userService->getUserByUuid((string)$uuidOrId);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        return view('App\Modules\User\Views\qr_card', [
            'title' => 'ID Card & Member QR - ' . $user['full_name'],
            'user'  => $user,
        ]);
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        $user   = $this->userService->getUserById($userId);

        return view('App\Modules\User\Views\profile', [
            'title' => 'Profil Saya - Multimedia Club',
            'user'  => $user,
        ]);
    }

    public function updateProfile()
    {
        $userId          = session()->get('user_id');
        $roleSlug        = session()->get('role_slug');
        $canEditUsername = in_array($roleSlug, ['superadmin', 'pembina', 'bph']);

        $rules = [
            'full_name'        => 'required|min_length[3]',
            'email'            => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'nis_nip'          => 'permit_empty',
            'phone'            => 'permit_empty',
            'class_dept'       => 'permit_empty',
            'address'          => 'permit_empty',
            'birth_date'       => 'permit_empty',
            'social_instagram' => 'permit_empty',
            'social_tiktok'    => 'permit_empty',
            'social_facebook'  => 'permit_empty',
            'social_linkedin'  => 'permit_empty',
            'social_github'    => 'permit_empty',
        ];

        if ($canEditUsername) {
            $rules['username'] = "required|alpha_numeric_punct|is_unique[users.username,id,{$userId}]";
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $postData = $this->request->getPost();
        if (!empty($postData['class_grade']) && !empty($postData['class_room']) && !empty($postData['division'])) {
            $postData['class_dept'] = trim($postData['class_grade']) . ' ' . trim($postData['class_room']) . ' - ' . trim($postData['division']);
        }

        $avatarFile = $this->request->getFile('avatar');
        $result     = $this->userService->updateSelfProfile($userId, $postData, $avatarFile, $canEditUsername);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        $notificationModel = new \App\Models\NotificationModel();
        $notificationModel->notifyUser(
            $userId,
            'Pembaruan Profil Akun Berhasil',
            'Data diri profil atau kata sandi akun Anda telah berhasil diperbarui.',
            'profile',
            base_url('profile')
        );

        return redirect()->back()->with('success', $result['body']['message']);
    }

    public function bulkUpdate()
    {
        $userIds = $this->request->getPost('user_ids');
        if (!is_array($userIds) || empty($userIds)) {
            return redirect()->back()->with('error', 'Pilih minimal satu anggota untuk diubah.');
        }

        if (session()->get('role_slug') !== 'superadmin' && $this->request->getPost('change_role') && (int)$this->request->getPost('role_id') === 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk memberikan role Super Admin secara massal.');
        }

        if (session()->get('role_slug') === 'bph' && $this->request->getPost('change_role') && (int)$this->request->getPost('role_id') === 2) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk memberikan role Pembina secara massal.');
        }

        if (!$this->canModifyUser($userIds)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk mengubah pengguna dengan role Super Admin atau Pembina yang terpilih.');
        }

        $postData = $this->request->getPost();
        if (!empty($postData['change_class']) && !empty($postData['class_grade']) && !empty($postData['class_room']) && !empty($postData['division'])) {
            $postData['class_dept'] = trim($postData['class_grade']) . ' ' . trim($postData['class_room']) . ' - ' . trim($postData['division']);
        }

        $result = $this->userService->bulkUpdateUsers($userIds, $postData, session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->to('/admin/users')->with('success', $result['body']['message']);
    }

    public function bulkAction()
    {
        $userIds = $this->request->getPost('user_ids');
        $action  = $this->request->getPost('action');

        if (!is_array($userIds) || empty($userIds)) {
            return redirect()->back()->with('error', 'Pilih minimal satu anggota.');
        }

        if (!$this->canModifyUser($userIds)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk memproses aksi pada pengguna dengan role Super Admin atau Pembina yang terpilih.');
        }

        $result = $this->userService->bulkActionUsers($userIds, (string)$action, session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->to('/admin/users')->with('success', $result['body']['message']);
    }
}
