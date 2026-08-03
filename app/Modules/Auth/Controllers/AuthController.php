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

        return view('App\Modules\Auth\Views\register', [
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

        $result = $this->authService->registerMember($this->request->getPost());

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
