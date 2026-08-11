<?php

namespace App\Modules\Cms\Controllers;

use App\Controllers\BaseController;

class OrgCmsController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $structures = $db->table('org_structures')->orderBy('sort_order', 'ASC')->get()->getResultArray();

        return view('App\Modules\Cms\Views\structure\index', [
            'title'      => 'Manajemen Bagan Organisasi & Pengurus',
            'structures' => $structures,
        ]);
    }

    public function store()
    {
        @ini_set('upload_max_filesize', '10M');
        @ini_set('post_max_size', '12M');

        $db       = \Config\Database::connect();
        $name     = trim($this->request->getPost('name') ?? '');
        $position = trim($this->request->getPost('position') ?? '');

        if (empty($name) || empty($position)) {
            return redirect()->back()->withInput()->with('error', 'Nama dan Jabatan wajib diisi.');
        }

        $photoPath = trim($this->request->getPost('photo') ?? '');

        $photoFile = $this->request->getFile('photo_file');
        if ($photoFile && $photoFile->getError() !== UPLOAD_ERR_NO_FILE) {
            if ($photoFile->getError() === UPLOAD_ERR_INI_SIZE || $photoFile->getSize() > 10 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran foto terlalu besar. Batas maksimal ukuran foto adalah 10MB.');
            }

            if (!$photoFile->isValid()) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengunggah foto: ' . $photoFile->getErrorString() . ' (Error Code: ' . $photoFile->getError() . ')');
            }

            $uploadDir = FCPATH . 'uploads/cms/structures/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }

            $newName = $photoFile->getRandomName();
            $photoFile->move($uploadDir, $newName);
            $photoPath = 'uploads/cms/structures/' . $newName;
        }

        $db->table('org_structures')->insert([
            'name'       => $name,
            'position'   => $position,
            'photo'      => $photoPath,
            'bio'        => trim($this->request->getPost('bio') ?? ''),
            'instagram'  => trim($this->request->getPost('instagram') ?? ''),
            'linkedin'   => trim($this->request->getPost('linkedin') ?? ''),
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
            'status'     => 'active',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/cms/structure')->with('success', 'Pengurus organisasi berhasil ditambahkan.');
    }

    public function update(int $id)
    {
        @ini_set('upload_max_filesize', '10M');
        @ini_set('post_max_size', '12M');

        $db       = \Config\Database::connect();
        $name     = trim($this->request->getPost('name') ?? '');
        $position = trim($this->request->getPost('position') ?? '');

        if (empty($name) || empty($position)) {
            return redirect()->back()->withInput()->with('error', 'Nama dan Jabatan wajib diisi.');
        }

        $existing = $db->table('org_structures')->where('id', $id)->get()->getRowArray();
        if (!$existing) {
            return redirect()->to('/admin/cms/structure')->with('error', 'Data pengurus tidak ditemukan.');
        }

        $photoPath = $existing['photo'];

        if ($this->request->getPost('remove_photo') === '1') {
            if (!empty($existing['photo']) && file_exists(FCPATH . $existing['photo'])) {
                @unlink(FCPATH . $existing['photo']);
            }
            $photoPath = '';
        }

        $inputPhotoUrl = trim($this->request->getPost('photo') ?? '');
        if ($inputPhotoUrl !== '' && $inputPhotoUrl !== $existing['photo']) {
            $photoPath = $inputPhotoUrl;
        }

        $photoFile = $this->request->getFile('photo_file');
        if ($photoFile && $photoFile->getError() !== UPLOAD_ERR_NO_FILE) {
            if ($photoFile->getError() === UPLOAD_ERR_INI_SIZE || $photoFile->getSize() > 10 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran foto terlalu besar. Batas maksimal ukuran foto adalah 10MB.');
            }

            if (!$photoFile->isValid()) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengunggah foto: ' . $photoFile->getErrorString() . ' (Error Code: ' . $photoFile->getError() . ')');
            }

            $uploadDir = FCPATH . 'uploads/cms/structures/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }

            if (!empty($existing['photo']) && file_exists(FCPATH . $existing['photo'])) {
                @unlink(FCPATH . $existing['photo']);
            }

            $newName = $photoFile->getRandomName();
            $photoFile->move($uploadDir, $newName);
            $photoPath = 'uploads/cms/structures/' . $newName;
        }

        $db->table('org_structures')->where('id', $id)->update([
            'name'       => $name,
            'position'   => $position,
            'photo'      => $photoPath,
            'bio'        => trim($this->request->getPost('bio') ?? ''),
            'instagram'  => trim($this->request->getPost('instagram') ?? ''),
            'linkedin'   => trim($this->request->getPost('linkedin') ?? ''),
            'sort_order' => (int)($this->request->getPost('sort_order') ?? 0),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/cms/structure')->with('success', 'Data Pengurus berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $db = \Config\Database::connect();
        $existing = $db->table('org_structures')->where('id', $id)->get()->getRowArray();
        if ($existing && !empty($existing['photo']) && file_exists(FCPATH . $existing['photo'])) {
            @unlink(FCPATH . $existing['photo']);
        }
        $db->table('org_structures')->where('id', $id)->delete();
        return redirect()->to('/admin/cms/structure')->with('success', 'Pengurus berhasil dihapus.');
    }
}
