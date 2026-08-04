<?php

namespace App\Modules\Auth\Services;

use App\Services\BaseService;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\AuditLogModel;

class AuthService extends BaseService
{
    protected $userModel;
    protected $roleModel;
    protected $auditLogModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel     = new UserModel();
        $this->roleModel     = new RoleModel();
        $this->auditLogModel = new AuditLogModel();
    }

    public function attemptLogin(string $loginInput, string $password): array
    {
        $loginInput = trim($loginInput);

        if (empty($loginInput) || empty($password)) {
            return $this->error('Username/Email dan Password wajib diisi.');
        }

        $user = $this->userModel->select('users.*, roles.name as role_name, roles.slug as role_slug')
                               ->join('roles', 'roles.id = users.role_id')
                               ->groupStart()
                                   ->where('users.username', $loginInput)
                                   ->orWhere('users.email', $loginInput)
                               ->groupEnd()
                               ->first();

        if (!$user) {
            $this->auditLogModel->recordLog(null, 'LOGIN_FAILED', "Percobaan login gagal untuk identifier: {$loginInput}");
            return $this->error('Username atau password tidak ditemukan.');
        }

        if ($user['status'] !== 'active') {
            $this->auditLogModel->recordLog($user['id'], 'LOGIN_BLOCKED', "Akun dalam status {$user['status']}");
            if ($user['status'] === 'inactive') {
                return $this->error('Akun Anda belum diaktifkan oleh Pembina / Admin. Silakan tunggu konfirmasi aktivasi agar akun Anda dapat digunakan untuk masuk.');
            }
            if ($user['status'] === 'left' || $user['status'] === 'keluar') {
                return $this->error('Anda keluar ekskul multimedia.');
            }
            return $this->error('Akun Anda non-aktif atau ditangguhkan. Silakan hubungi Pembina / Admin.');
        }

        if (!password_verify($password, $user['password_hash'])) {
            $this->auditLogModel->recordLog($user['id'], 'LOGIN_FAILED', 'Password salah');
            return $this->error('Username atau password tidak sesuai.');
        }

        $sessionData = [
            'user_id'      => $user['id'],
            'member_uuid'  => $user['member_uuid'],
            'username'     => $user['username'],
            'email'        => $user['email'],
            'full_name'    => $user['full_name'],
            'nis_nip'      => $user['nis_nip'],
            'class_dept'   => $user['class_dept'],
            'role_id'      => $user['role_id'],
            'role_slug'    => $user['role_slug'],
            'role_name'    => $user['role_name'],
            'avatar'       => $user['avatar'],
            'is_logged_in' => true,
        ];

        session()->set($sessionData);
        $this->auditLogModel->recordLog($user['id'], 'LOGIN_SUCCESS', "User {$user['username']} berhasil login dengan role {$user['role_name']}");

        return $this->success("Selamat datang kembali, {$user['full_name']}!", $sessionData);
    }

    public function registerMember(array $data): array
    {
        $this->beginTransaction();

        try {
            $memberRole = $this->roleModel->getRoleBySlug('member');

            $userId = $this->userModel->insert([
                'member_uuid'   => $this->userModel->generateUuid(),
                'role_id'       => $memberRole['id'] ?? 4,
                'username'      => trim($data['username']),
                'email'         => trim($data['email']),
                'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
                'full_name'     => trim($data['full_name']),
                'nis_nip'       => trim($data['nis_nip']),
                'class_dept'    => trim($data['class_dept']),
                'phone'         => trim($data['phone']),
                'qr_version'    => 1,
                'qr_updated_at' => date('Y-m-d H:i:s'),
                'status'        => 'inactive',
            ]);

            $this->auditLogModel->recordLog($userId, 'REGISTER_SUCCESS', "Anggota baru {$data['full_name']} mendaftar akun (menunggu konfirmasi admin)");

            $this->commitTransaction();
            return $this->success('Pendaftaran berhasil! Akun Anda saat ini dalam proses peninjauan dan menunggu konfirmasi/aktivasi dari Pembina/Admin.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $this->error('Gagal memproses pendaftaran: ' . $e->getMessage());
        }
    }

    public function logout(): void
    {
        $userId = session()->get('user_id');
        if ($userId) {
            $this->auditLogModel->recordLog($userId, 'LOGOUT', 'User keluar dari sistem');
        }
        session()->destroy();
    }
}
