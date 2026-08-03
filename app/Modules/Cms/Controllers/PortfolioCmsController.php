<?php

namespace App\Modules\Cms\Controllers;

use App\Controllers\BaseController;

class PortfolioCmsController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $portfolios = $db->table('portfolios')->orderBy('id', 'DESC')->get()->getResultArray();

        foreach ($portfolios as &$p) {
            $p['contributors'] = $db->table('portfolio_contributors')
                                    ->select('portfolio_contributors.user_id, users.full_name, users.avatar, portfolio_contributors.role_title')
                                    ->join('users', 'users.id = portfolio_contributors.user_id')
                                    ->where('portfolio_id', $p['id'])
                                    ->get()->getResultArray();
        }

        $members = $db->table('users')->where('status', 'active')->get()->getResultArray();

        return view('App\Modules\Cms\Views\portfolios\index', [
            'title'      => 'Manajemen Portofolio & Kontributor',
            'portfolios' => $portfolios,
            'members'    => $members,
        ]);
    }

    public function store()
    {
        $db          = \Config\Database::connect();
        $title       = trim($this->request->getPost('title') ?? '');
        $category    = trim($this->request->getPost('category') ?: 'Videography');
        $thumbnail   = trim($this->request->getPost('thumbnail') ?? '');
        $description = trim($this->request->getPost('description') ?? '');
        $year        = trim($this->request->getPost('year') ?: date('Y'));
        $externalUrl = trim($this->request->getPost('external_url') ?? '');
        $contributors= $this->request->getPost('contributors') ?? [];

        if (empty($title)) {
            return redirect()->back()->with('error', 'Judul proyek portofolio wajib diisi.');
        }

        $db->transStart();

        $db->table('portfolios')->insert([
            'title'        => $title,
            'category'     => $category,
            'thumbnail'    => $thumbnail,
            'description'  => $description,
            'year'         => $year,
            'external_url' => $externalUrl,
            'is_featured'  => isset($_POST['is_featured']) ? 1 : 0,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        $portfolioId = $db->insertID();

        // Multi-Contributors Insertion
        if (is_array($contributors)) {
            foreach ($contributors as $userId) {
                $db->table('portfolio_contributors')->insert([
                    'portfolio_id' => $portfolioId,
                    'user_id'      => (int)$userId,
                    'role_title'   => 'Kontributor Utama',
                    'created_at'   => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $db->transComplete();

        return redirect()->to('/admin/cms/portfolios')->with('success', 'Portofolio karya & multi-kontributor berhasil ditambahkan.');
    }

    public function update(int $id)
    {
        $db          = \Config\Database::connect();
        $title       = trim($this->request->getPost('title') ?? '');
        $contributors= $this->request->getPost('contributors') ?? [];

        if (empty($title)) {
            return redirect()->back()->with('error', 'Judul proyek portofolio wajib diisi.');
        }

        $db->transStart();

        $db->table('portfolios')->where('id', $id)->update([
            'title'        => $title,
            'category'     => trim($this->request->getPost('category') ?: 'Videography'),
            'thumbnail'    => trim($this->request->getPost('thumbnail') ?? ''),
            'description'  => trim($this->request->getPost('description') ?? ''),
            'year'         => trim($this->request->getPost('year') ?: date('Y')),
            'external_url' => trim($this->request->getPost('external_url') ?? ''),
            'is_featured'  => isset($_POST['is_featured']) ? 1 : 0,
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        // Sync Multi-Contributors
        $db->table('portfolio_contributors')->where('portfolio_id', $id)->delete();
        if (is_array($contributors)) {
            foreach ($contributors as $userId) {
                $db->table('portfolio_contributors')->insert([
                    'portfolio_id' => $id,
                    'user_id'      => (int)$userId,
                    'role_title'   => 'Kontributor Utama',
                    'created_at'   => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $db->transComplete();

        return redirect()->to('/admin/cms/portfolios')->with('success', 'Data Portofolio berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $db = \Config\Database::connect();
        $db->table('portfolios')->where('id', $id)->delete();
        return redirect()->to('/admin/cms/portfolios')->with('success', 'Portofolio berhasil dihapus.');
    }
}
