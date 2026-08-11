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
            'username'         => 'required|regex_match[/^\S+$/]|alpha_numeric_punct|min_length[3]|is_unique[users.username]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'nis_nip'          => 'required|min_length[4]',
            'class_grade'      => 'required|in_list[X,XI,XII]',
            'class_room'       => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[10]',
            'division'         => 'required|in_list[Broadcasting,Programming]',
            'phone'            => 'required|numeric|min_length[10]',
            'address'          => 'permit_empty|max_length[500]',
            'birth_date'       => 'permit_empty|valid_date[Y-m-d]',
            'social_instagram' => 'permit_empty|max_length[255]',
            'social_tiktok'    => 'permit_empty|max_length[255]',
            'social_facebook'  => 'permit_empty|max_length[255]',
            'social_linkedin'  => 'permit_empty|max_length[255]',
            'social_github'    => 'permit_empty|max_length[255]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'matches[password]',
        ];

        $customErrors = [
            'username' => [
                'regex_match' => 'Username tidak boleh mengandung karakter spasi. Silakan gunakan huruf, angka, garis bawah (_), atau titik (.).',
                'is_unique'   => 'Username tersebut sudah terdaftar, silakan gunakan username lain.',
            ]
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
