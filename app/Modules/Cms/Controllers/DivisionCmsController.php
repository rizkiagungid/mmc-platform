<?php

namespace App\Modules\Cms\Controllers;

use App\Controllers\BaseController;

class DivisionCmsController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $divisions = $db->table('divisions')->orderBy('sort_order', 'ASC')->get()->getResultArray();
        
        foreach ($divisions as &$div) {
            $div['programs'] = $db->table('learning_programs')
                                 ->where('division_id', $div['id'])
                                 ->orderBy('sort_order', 'ASC')
                                 ->get()->getResultArray();
        }

        return view('App\Modules\Cms\Views\divisions\index', [
            'title'     => 'Manajemen Divisi & Learning Program',
            'divisions' => $divisions,
        ]);
    }

    public function storeDivision()
    {
        $db   = \Config\Database::connect();
        $name = trim($this->request->getPost('name') ?? '');
        $slug = mb_url_title($name, '-', true);

        if (empty($name)) {
            return redirect()->back()->with('error', 'Nama divisi wajib diisi.');
        }

        $db->table('divisions')->insert([
            'slug'              => $slug ?: 'divisi-' . time(),
            'name'              => $name,
            'icon'              => trim($this->request->getPost('icon') ?: 'fa-layer-group'),
            'cover_image'       => trim($this->request->getPost('cover_image') ?? ''),
            'short_description' => trim($this->request->getPost('short_description') ?? ''),
            'full_description'  => trim($this->request->getPost('full_description') ?? ''),
            'sort_order'        => (int)($this->request->getPost('sort_order') ?? 0),
            'status'            => 'active',
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/cms/divisions')->with('success', 'Divisi baru berhasil ditambahkan.');
    }

    public function updateDivision(int $id)
    {
        $db   = \Config\Database::connect();
        $name = trim($this->request->getPost('name') ?? '');

        if (empty($name)) {
            return redirect()->back()->with('error', 'Nama divisi wajib diisi.');
        }

        $db->table('divisions')->where('id', $id)->update([
            'name'              => $name,
            'icon'              => trim($this->request->getPost('icon') ?: 'fa-layer-group'),
            'cover_image'       => trim($this->request->getPost('cover_image') ?? ''),
            'short_description' => trim($this->request->getPost('short_description') ?? ''),
            'full_description'  => trim($this->request->getPost('full_description') ?? ''),
            'sort_order'        => (int)($this->request->getPost('sort_order') ?? 0),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/cms/divisions')->with('success', 'Data Divisi berhasil diperbarui.');
    }

    public function storeProgram()
    {
        $db         = \Config\Database::connect();
        $divisionId = (int)$this->request->getPost('division_id');
        $title      = trim($this->request->getPost('title') ?? '');

        if ($divisionId <= 0 || empty($title)) {
            return redirect()->back()->with('error', 'Divisi dan Judul Program wajib diisi.');
        }

        $db->table('learning_programs')->insert([
            'division_id' => $divisionId,
            'title'       => $title,
            'description' => trim($this->request->getPost('description') ?? ''),
            'difficulty'  => trim($this->request->getPost('difficulty') ?: 'Pemula (Basic)'),
            'duration'    => trim($this->request->getPost('duration') ?: '2 Sesi'),
            'sort_order'  => (int)($this->request->getPost('sort_order') ?? 0),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/cms/divisions')->with('success', 'Learning Program berhasil ditambahkan.');
    }

    public function updateProgram(int $id)
    {
        $db    = \Config\Database::connect();
        $title = trim($this->request->getPost('title') ?? '');

        if (empty($title)) {
            return redirect()->back()->with('error', 'Judul Program wajib diisi.');
        }

        $db->table('learning_programs')->where('id', $id)->update([
            'title'       => $title,
            'description' => trim($this->request->getPost('description') ?? ''),
            'difficulty'  => trim($this->request->getPost('difficulty') ?: 'Pemula (Basic)'),
            'duration'    => trim($this->request->getPost('duration') ?: '2 Sesi'),
            'sort_order'  => (int)($this->request->getPost('sort_order') ?? 0),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/cms/divisions')->with('success', 'Learning Program berhasil diperbarui.');
    }

    public function deleteDivision(int $id)
    {
        $db = \Config\Database::connect();
        $db->table('divisions')->where('id', $id)->delete();
        return redirect()->to('/admin/cms/divisions')->with('success', 'Divisi berhasil dihapus.');
    }

    public function deleteProgram(int $id)
    {
        $db = \Config\Database::connect();
        $db->table('learning_programs')->where('id', $id)->delete();
        return redirect()->to('/admin/cms/divisions')->with('success', 'Learning Program berhasil dihapus.');
    }
}
