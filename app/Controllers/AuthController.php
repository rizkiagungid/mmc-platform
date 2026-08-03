<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\AuditLogModel;

class AuthController extends BaseController
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

    public function login()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login', [
            'title' => 'Login Member Portal - Multimedia Club',
        ]);
    }

    public function attemptLogin()
    {
        $loginInput = trim($this->request->getPost('login') ?? '');
        $password   = $this->request->getPost('password') ?? '';

        if (empty($loginInput) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Username/Email dan Password wajib diisi.');
        }

        // Search by username or email
        $user = $this->userModel->select('users.*, roles.name as role_name, roles.slug as role_slug')
                               ->join('roles', 'roles.id = users.role_id')
                               ->groupStart()
                                   ->where('users.username', $loginInput)
                                   ->orWhere('users.email', $loginInput)
                               ->groupEnd()
                               ->first();

        if (!$user) {
            $this->auditLogModel->recordLog(null, 'LOGIN_FAILED', "Percobaan login gagal untuk identifier: {$loginInput}");
            return redirect()->back()->withInput()->with('error', 'Username atau password tidak ditemukan.');
        }

        if ($user['status'] !== 'active') {
            $this->auditLogModel->recordLog($user['id'], 'LOGIN_BLOCKED', "Akun dalam status {$user['status']}");
            if ($user['status'] === 'left' || $user['status'] === 'keluar') {
                return redirect()->back()->withInput()->with('error', 'Anda keluar ekskul multimedia.');
            }
            return redirect()->back()->withInput()->with('error', 'Akun Anda nonaktif atau ditangguhkan. Silakan hubungi Pembina / Admin.');
        }

        if (!password_verify($password, $user['password_hash'])) {
            $this->auditLogModel->recordLog($user['id'], 'LOGIN_FAILED', 'Password salah');
            return redirect()->back()->withInput()->with('error', 'Username atau password tidak sesuai.');
        }

        // Create Session
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

        return redirect()->to('/dashboard')->with('success', "Selamat datang kembali, {$user['full_name']}!");
    }

    public function register()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register', [
            'title' => 'Pendaftaran Anggota Baru - Multimedia Club',
        ]);
    }

    public function attemptRegister()
    {
        $rules = [
            'full_name'  => 'required|min_length[3]|max_length[100]',
            'username'   => 'required|alpha_numeric_punct|min_length[3]|is_unique[users.username]',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'nis_nip'    => 'required|min_length[4]',
            'class_dept' => 'required',
            'phone'      => 'required|numeric|min_length[10]',
            'password'   => 'required|min_length[6]',
            'confirm_password' => 'matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $memberRole = $this->roleModel->getRoleBySlug('member');

        $userId = $this->userModel->insert([
            'member_uuid'   => $this->userModel->generateUuid(),
            'role_id'       => $memberRole['id'] ?? 4,
            'username'      => trim($this->request->getPost('username')),
            'email'         => trim($this->request->getPost('email')),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'full_name'     => trim($this->request->getPost('full_name')),
            'nis_nip'       => trim($this->request->getPost('nis_nip')),
            'class_dept'    => trim($this->request->getPost('class_dept')),
            'phone'         => trim($this->request->getPost('phone')),
            'qr_version'    => 1,
            'qr_updated_at' => date('Y-m-d H:i:s'),
            'status'        => 'active',
        ]);

        $this->auditLogModel->recordLog($userId, 'REGISTER_SUCCESS', "Anggota baru {$this->request->getPost('full_name')} mendaftar akun");

        return redirect()->to('/login')->with('success', 'Pendaftaran berhasil! Silakan login menggunakan akun baru Anda.');
    }

    public function logout()
    {
        $userId = session()->get('user_id');
        if ($userId) {
            $this->auditLogModel->recordLog($userId, 'LOGOUT', 'User keluar dari sistem');
        }

        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
