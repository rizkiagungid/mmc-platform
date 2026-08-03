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

    public function index()
    {
        $keyword = trim($this->request->getGet('keyword') ?? '');
        $roleId  = $this->request->getGet('role_id') ? (int)$this->request->getGet('role_id') : null;

        $users = $this->userService->getAllUsers($roleId, $keyword);
        $roles = $this->userService->getAllRoles();

        return view('App\Modules\User\Views\index', [
            'title'   => 'Manajemen Pengguna & Anggota - Admin CMS',
            'users'   => $users,
            'roles'   => $roles,
            'keyword' => $keyword,
            'roleId'  => $roleId,
        ]);
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

        $result = $this->userService->createUser($this->request->getPost(), session()->get('user_id'));

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

        $result = $this->userService->updateUser((int)$id, $this->request->getPost(), session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->to('/admin/users')->with('success', $result['body']['message']);
    }

    public function delete($id)
    {
        $result = $this->userService->deleteUser((int)$id, session()->get('user_id'));

        if ($result['body']['status'] !== 'success') {
            return redirect()->to('/admin/users')->with('error', $result['body']['message']);
        }

        return redirect()->to('/admin/users')->with('success', $result['body']['message']);
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

        $avatarFile = $this->request->getFile('avatar');
        $result     = $this->userService->updateSelfProfile($userId, $this->request->getPost(), $avatarFile);

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->withInput()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
    }
}
