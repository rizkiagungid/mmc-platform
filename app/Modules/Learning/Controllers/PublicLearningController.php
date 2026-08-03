<?php

namespace App\Modules\Learning\Controllers;

use App\Controllers\BaseController;
use App\Modules\Learning\Services\LearningService;

class PublicLearningController extends BaseController
{
    protected LearningService $learningService;
    protected $db;

    public function __construct()
    {
        $this->learningService = new LearningService();
        $this->db              = \Config\Database::connect();
    }

    public function index()
    {
        $filters = [
            'search'      => $this->request->getGet('q'),
            'division_id' => $this->request->getGet('div'),
            'category'    => $this->request->getGet('cat'),
            'tag'         => $this->request->getGet('tag'),
            'sort'        => $this->request->getGet('sort') ?: 'latest',
        ];

        $materials = $this->learningService->getPublicMaterials($filters);
        $featured  = $this->learningService->getPublicMaterials(['sort' => 'featured']);
        $divisions = $this->db->table('divisions')->where('status', 'active')->get()->getResultArray();
        $allTags   = $this->learningService->getAllTags();

        return view('App\Modules\Learning\Views\public\index', [
            'title'     => 'Learning Center & Materi Pembelajaran - Multimedia Club SMAN 1 Tamansari',
            'materials' => $materials,
            'featured'  => array_slice($featured, 0, 3),
            'divisions' => $divisions,
            'allTags'   => $allTags,
            'filters'   => $filters,
        ]);
    }

    public function detail(string $slug)
    {
        $material = $this->learningService->getMaterialBySlug($slug, true);
        if (!$material) {
            // Check if material exists but is draft or deleted for admin preview
            if (session()->get('is_logged_in') && in_array(session()->get('role_slug'), ['superadmin', 'pembina', 'bph'])) {
                $material = $this->learningService->getMaterialBySlug($slug, false);
            }
            if (!$material) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Materi pembelajaran dengan URL '{$slug}' tidak ditemukan.");
            }
        }

        // Check Member Visibility Gate
        if ($material['visibility'] === 'member' && !session()->get('is_logged_in')) {
            return redirect()->to('/login')->with('error', 'Materi ini khusus untuk Anggota Multimedia Club. Silakan login terlebih dahulu untuk mengakses isi materi.');
        }

        // Increment Views Count
        $this->learningService->incrementViewCount($material['id']);

        // Fetch Related Materials (4-tier algorithm)
        $related = $this->learningService->getRelatedMaterials($material, 4);

        return view('App\Modules\Learning\Views\public\detail', [
            'title'    => esc($material['title']) . ' - Learning Center MMC',
            'material' => $material,
            'related'  => $related,
        ]);
    }
}
