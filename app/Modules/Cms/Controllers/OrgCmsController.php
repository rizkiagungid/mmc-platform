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
        $db       = \Config\Database::connect();
        $name     = trim($this->request->getPost('name') ?? '');
        $position = trim($this->request->getPost('position') ?? '');

        if (empty($name) || empty($position)) {
            return redirect()->back()->with('error', 'Nama dan Jabatan wajib diisi.');
        }

        $db->table('org_structures')->insert([
            'name'       => $name,
            'position'   => $position,
            'photo'      => trim($this->request->getPost('photo') ?? ''),
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
        $db       = \Config\Database::connect();
        $name     = trim($this->request->getPost('name') ?? '');
        $position = trim($this->request->getPost('position') ?? '');

        if (empty($name) || empty($position)) {
            return redirect()->back()->with('error', 'Nama dan Jabatan wajib diisi.');
        }

        $db->table('org_structures')->where('id', $id)->update([
            'name'       => $name,
            'position'   => $position,
            'photo'      => trim($this->request->getPost('photo') ?? ''),
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
        $db->table('org_structures')->where('id', $id)->delete();
        return redirect()->to('/admin/cms/structure')->with('success', 'Pengurus berhasil dihapus.');
    }
}
