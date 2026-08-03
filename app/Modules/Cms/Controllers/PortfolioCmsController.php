<?php

namespace App\Modules\Cms\Controllers;

use App\Controllers\BaseController;

class PortfolioCmsController extends BaseController
{
    private function ensureColumnsExist($db)
    {
        if (!$db->fieldExists('media_file', 'portfolios')) {
            $forge = \Config\Database::forge();
            $forge->addColumn('portfolios', [
                'media_file'    => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
                'media_gallery' => ['type' => 'TEXT', 'null' => true],
            ]);
        }
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $this->ensureColumnsExist($db);

        $portfolios = $db->table('portfolios')->orderBy('id', 'DESC')->get()->getResultArray();

        foreach ($portfolios as &$p) {
            $p['contributors'] = $db->table('portfolio_contributors')
                                    ->select('portfolio_contributors.user_id, users.full_name, users.avatar, portfolio_contributors.role_title')
                                    ->join('users', 'users.id = portfolio_contributors.user_id')
                                    ->where('portfolio_id', $p['id'])
                                    ->where('users.deleted_at IS NULL')
                                    ->get()->getResultArray();
        }

        $members = $db->table('users')
                      ->where('status', 'active')
                      ->where('deleted_at IS NULL')
                      ->orderBy('full_name', 'ASC')
                      ->get()->getResultArray();

        return view('App\Modules\Cms\Views\portfolios\index', [
            'title'      => 'Manajemen Portofolio & Kontributor',
            'portfolios' => $portfolios,
            'members'    => $members,
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $this->ensureColumnsExist($db);

        $title       = trim($this->request->getPost('title') ?? '');
        $category    = trim($this->request->getPost('category') ?: 'Broadcasting');
        $thumbnail   = trim($this->request->getPost('thumbnail') ?? '');
        $description = trim($this->request->getPost('description') ?? '');
        $year        = trim($this->request->getPost('year') ?: date('Y'));
        $externalUrl = trim($this->request->getPost('external_url') ?? '');
        $contributors= $this->request->getPost('contributors') ?? [];

        if (empty($title)) {
            return redirect()->back()->with('error', 'Judul proyek portofolio wajib diisi.');
        }

        $uploadDir = FCPATH . 'uploads/portfolios';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        // Handle Thumbnail File Upload
        $thumbFile = $this->request->getFile('thumbnail_file');
        if ($thumbFile && $thumbFile->isValid() && !$thumbFile->hasMoved()) {
            $newName = $thumbFile->getRandomName();
            $thumbFile->move($uploadDir, $newName);
            $thumbnail = 'uploads/portfolios/' . $newName;
        }

        // Handle Single Direct Media File (Image/Video)
        $mediaPath = null;
        $mediaFile = $this->request->getFile('media_file_input');
        if ($mediaFile && $mediaFile->isValid() && !$mediaFile->hasMoved()) {
            $newName = $mediaFile->getRandomName();
            $mediaFile->move($uploadDir, $newName);
            $mediaPath = 'uploads/portfolios/' . $newName;
        }

        // Handle Multiple Gallery Files (Images/Videos)
        $galleryPaths = [];
        $galleryFiles = $this->request->getFileMultiple('gallery_files');
        if ($galleryFiles) {
            foreach ($galleryFiles as $gFile) {
                if ($gFile && $gFile->isValid() && !$gFile->hasMoved()) {
                    $newName = $gFile->getRandomName();
                    $gFile->move($uploadDir, $newName);
                    $galleryPaths[] = 'uploads/portfolios/' . $newName;
                }
            }
        }

        $db->transStart();

        $db->table('portfolios')->insert([
            'title'         => $title,
            'category'      => $category,
            'thumbnail'     => $thumbnail,
            'media_file'    => $mediaPath,
            'media_gallery' => !empty($galleryPaths) ? json_encode($galleryPaths) : null,
            'description'   => $description,
            'year'          => $year,
            'external_url'  => $externalUrl,
            'is_featured'   => isset($_POST['is_featured']) ? 1 : 0,
            'created_at'    => date('Y-m-d H:i:s'),
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
        $db = \Config\Database::connect();
        $this->ensureColumnsExist($db);

        $title       = trim($this->request->getPost('title') ?? '');
        $contributors= $this->request->getPost('contributors') ?? [];

        if (empty($title)) {
            return redirect()->back()->with('error', 'Judul proyek portofolio wajib diisi.');
        }

        $existing = $db->table('portfolios')->where('id', $id)->get()->getRowArray();
        if (!$existing) {
            return redirect()->back()->with('error', 'Portofolio tidak ditemukan.');
        }

        $thumbnail = trim($this->request->getPost('thumbnail') ?? ($existing['thumbnail'] ?? ''));
        $mediaPath = $existing['media_file'] ?? null;
        $galleryPaths = !empty($existing['media_gallery']) ? (json_decode($existing['media_gallery'], true) ?: []) : [];

        $uploadDir = FCPATH . 'uploads/portfolios';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        // Handle Thumbnail Upload
        $thumbFile = $this->request->getFile('thumbnail_file');
        if ($thumbFile && $thumbFile->isValid() && !$thumbFile->hasMoved()) {
            $newName = $thumbFile->getRandomName();
            $thumbFile->move($uploadDir, $newName);
            $thumbnail = 'uploads/portfolios/' . $newName;
        }

        // Handle Single Direct Media File
        $mediaFile = $this->request->getFile('media_file_input');
        if ($mediaFile && $mediaFile->isValid() && !$mediaFile->hasMoved()) {
            $newName = $mediaFile->getRandomName();
            $mediaFile->move($uploadDir, $newName);
            $mediaPath = 'uploads/portfolios/' . $newName;
        }

        // Handle Additional Multiple Gallery Files
        $galleryFiles = $this->request->getFileMultiple('gallery_files');
        if ($galleryFiles) {
            foreach ($galleryFiles as $gFile) {
                if ($gFile && $gFile->isValid() && !$gFile->hasMoved()) {
                    $newName = $gFile->getRandomName();
                    $gFile->move($uploadDir, $newName);
                    $galleryPaths[] = 'uploads/portfolios/' . $newName;
                }
            }
        }

        $db->transStart();

        $db->table('portfolios')->where('id', $id)->update([
            'title'         => $title,
            'category'      => trim($this->request->getPost('category') ?: 'Broadcasting'),
            'thumbnail'     => $thumbnail,
            'media_file'    => $mediaPath,
            'media_gallery' => !empty($galleryPaths) ? json_encode(array_values(array_unique($galleryPaths))) : null,
            'description'   => trim($this->request->getPost('description') ?? ''),
            'year'          => trim($this->request->getPost('year') ?: date('Y')),
            'external_url'  => trim($this->request->getPost('external_url') ?? ''),
            'is_featured'   => isset($_POST['is_featured']) ? 1 : 0,
            'updated_at'    => date('Y-m-d H:i:s'),
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

    private function deleteLocalAsset(?string $relativePath)
    {
        if (empty($relativePath) || strpos($relativePath, 'http') === 0) {
            return;
        }

        $fullPath = FCPATH . ltrim($relativePath, '/\\');
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    public function delete(int $id)
    {
        $db = \Config\Database::connect();
        $portfolio = $db->table('portfolios')->where('id', $id)->get()->getRowArray();

        if ($portfolio) {
            // Hapus file thumbnail jika tersimpan secara lokal
            $this->deleteLocalAsset($portfolio['thumbnail'] ?? null);

            // Hapus file media utama (video/gambar) jika tersimpan secara lokal
            $this->deleteLocalAsset($portfolio['media_file'] ?? null);

            // Hapus seluruh file galeri karya jika tersimpan secara lokal
            if (!empty($portfolio['media_gallery'])) {
                $gallery = json_decode($portfolio['media_gallery'], true);
                if (is_array($gallery)) {
                    foreach ($gallery as $gFile) {
                        $this->deleteLocalAsset($gFile);
                    }
                }
            }

            // Hapus data portofolio & kontributor dari basis data
            $db->table('portfolios')->where('id', $id)->delete();
        }

        return redirect()->to('/admin/cms/portfolios')->with('success', 'Portofolio dan seluruh file aset terkait berhasil dihapus dari server.');
    }
}
