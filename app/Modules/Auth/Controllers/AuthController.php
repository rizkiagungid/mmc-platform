<?php

namespace App\Modules\Auth\Controllers;

use App\Controllers\BaseController;
use App\Modules\Auth\Services\AuthService;

class AuthController extends BaseController
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('App\Modules\Auth\Views\login', [
            'title' => 'Login Member Portal - Multimedia Club',
        ]);
    }

    public function attemptLogin()
    {
        $loginInput = $this->request->getPost('login');
        $password   = $this->request->getPost('password');

        $result = $this->authService->attemptLogin((string)$loginInput, (string)$password);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->to('/dashboard')->with('success', $result['body']['message']);
    }

    public function register()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        $settingModel = new \App\Models\SettingModel();
        if ($settingModel->getSetting('enable_registration', '1') === '0') {
            return redirect()->to('/login')->with('error', 'Pendaftaran akun baru saat ini sedang ditutup oleh Administrator.');
        }

        return view('App\Modules\Auth\Views\register', [
            'title' => 'Pendaftaran Anggota Baru - Multimedia Club',
        ]);
    }

    public function attemptRegister()
    {
        $settingModel = new \App\Models\SettingModel();
        if ($settingModel->getSetting('enable_registration', '1') === '0') {
            return redirect()->to('/login')->with('error', 'Pendaftaran akun baru saat ini sedang ditutup oleh Administrator.');
        }

        $usernameInput = (string)$this->request->getPost('username');
        if (preg_match('/\s/', $usernameInput)) {
            return redirect()->back()->withInput()->with('error', 'Pendaftaran gagal: Username tidak boleh mengandung spasi! Silakan ganti spasi dengan garis bawah (_) atau titik (.).');
        }

        $rules = [
            'full_name'        => 'required|min_length[3]|max_length[100]',
            'username'         => 'required|regex_match[/^\S+$/]|alpha_numeric_punct|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'nis_nip'          => 'required|min_length[4]|max_length[30]',
            'class_grade'      => 'required|in_list[X,XI,XII]',
            'class_room'       => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[10]',
            'division'         => 'required|in_list[Broadcasting,Programming]',
            'phone'            => 'required|numeric|min_length[10]|max_length[16]',
            'address'          => 'permit_empty|max_length[500]',
            'birth_date'       => 'permit_empty|valid_date[Y-m-d]',
            'social_instagram' => 'permit_empty|max_length[255]',
            'social_tiktok'    => 'permit_empty|max_length[255]',
            'social_facebook'  => 'permit_empty|max_length[255]',
            'social_linkedin'  => 'permit_empty|max_length[255]',
            'social_github'    => 'permit_empty|max_length[255]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ];

        $customErrors = [
            'full_name' => [
                'required'   => 'Nama Lengkap wajib diisi.',
                'min_length' => 'Nama Lengkap terlalu pendek, minimal 3 karakter.',
                'max_length' => 'Nama Lengkap tidak boleh lebih dari 100 karakter.',
            ],
            'username' => [
                'required'            => 'Username wajib diisi.',
                'regex_match'         => 'Username tidak boleh mengandung spasi! Silakan gunakan huruf, angka, garis bawah (_), atau titik (.).',
                'alpha_numeric_punct' => 'Username hanya boleh memuat huruf, angka, titik (.), atau garis bawah (_) tanpa spasi.',
                'min_length'          => 'Username terlalu pendek, minimal 3 karakter.',
                'max_length'          => 'Username maksimal 30 karakter.',
                'is_unique'           => 'Username ini sudah digunakan oleh akun lain. Silakan gunakan username lain.',
            ],
            'email' => [
                'required'    => 'Alamat Email wajib diisi.',
                'valid_email' => 'Format alamat email tidak valid. Contoh: nama@gmail.com',
                'is_unique'   => 'Alamat Email ini sudah terdaftar. Silakan gunakan email lain atau login jika sudah memiliki akun.',
            ],
            'nis_nip' => [
                'required'   => 'NIS / NIP wajib diisi.',
                'min_length' => 'NIS / NIP minimal 4 karakter.',
                'max_length' => 'NIS / NIP maksimal 30 karakter.',
            ],
            'class_grade' => [
                'required' => 'Silakan pilih Tingkat Kelas (X, XI, atau XII).',
                'in_list'  => 'Pilihan Tingkat Kelas tidak valid.',
            ],
            'class_room' => [
                'required'              => 'Silakan pilih Nomor Ruang Kelas (1 s.d. 10).',
                'integer'               => 'Nomor Ruang Kelas harus berupa angka.',
                'greater_than_equal_to' => 'Nomor Ruang Kelas minimal 1.',
                'less_than_equal_to'    => 'Nomor Ruang Kelas maksimal 10.',
            ],
            'division' => [
                'required' => 'Silakan pilih Divisi Peminatan (Broadcasting atau Programming).',
                'in_list'  => 'Pilihan Divisi Peminatan tidak valid.',
            ],
            'phone' => [
                'required'   => 'Nomor WhatsApp / HP wajib diisi.',
                'numeric'    => 'Nomor WhatsApp / HP hanya boleh berupa angka tanpa spasi atau tanda hubung.',
                'min_length' => 'Nomor WhatsApp / HP minimal 10 digit angka.',
                'max_length' => 'Nomor WhatsApp / HP tidak boleh lebih dari 16 digit.',
            ],
            'address' => [
                'max_length' => 'Alamat tempat tinggal tidak boleh lebih dari 500 karakter.',
            ],
            'birth_date' => [
                'valid_date' => 'Format Tanggal Lahir tidak valid.',
            ],
            'password' => [
                'required'   => 'Password (Kata Sandi) wajib diisi.',
                'min_length' => 'Password terlalu pendek! Password minimal harus terdiri dari 6 karakter.',
            ],
            'confirm_password' => [
                'required' => 'Konfirmasi Password wajib diisi.',
                'matches'  => 'Konfirmasi Password tidak sama / tidak cocok dengan Password yang Anda masukkan di atas.',
            ],
        ];

        if (!$this->validate($rules, $customErrors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $postData = $this->request->getPost();
        $postData['class_dept'] = trim($postData['class_grade']) . ' ' . trim($postData['class_room']) . ' - ' . trim($postData['division']);

        $result = $this->authService->registerMember($postData);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->to('/login')->with('success', $result['body']['message']);
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
