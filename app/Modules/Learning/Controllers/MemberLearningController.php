<?php

namespace App\Modules\Learning\Controllers;

use App\Controllers\BaseController;
use App\Modules\Learning\Services\LearningService;

class MemberLearningController extends BaseController
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

        // Members can see all published materials (public + member-only)
        $materials = $this->learningService->getPublicMaterials($filters);
        $divisions = $this->db->table('divisions')->where('status', 'active')->get()->getResultArray();
        $allTags   = $this->learningService->getAllTags();

        return view('App\Modules\Learning\Views\member\index', [
            'title'     => 'Materi Pembelajaran - Portal Anggota',
            'materials' => $materials,
            'divisions' => $divisions,
            'allTags'   => $allTags,
            'filters'   => $filters,
        ]);
    }

    public function detail(string $slug)
    {
        $material = $this->learningService->getMaterialBySlug($slug, true);
        if (!$material) {
            // Check if material exists for admin preview
            if (in_array(session()->get('role_slug'), ['superadmin', 'pembina', 'bph'])) {
                $material = $this->learningService->getMaterialBySlug($slug, false);
            }
            if (!$material) {
                return redirect()->to('/member/learning')->with('error', "Materi pembelajaran dengan URL '{$slug}' tidak ditemukan.");
            }
        }

        // Increment Views Count
        $this->learningService->incrementViewCount($material['id']);

        // Fetch Related Materials (4-tier algorithm)
        $related = $this->learningService->getRelatedMaterials($material, 4);

        return view('App\Modules\Learning\Views\member\detail', [
            'title'    => esc($material['title']) . ' - Portal Anggota',
            'material' => $material,
            'related'  => $related,
        ]);
    }
}
