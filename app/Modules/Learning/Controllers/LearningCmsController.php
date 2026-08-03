<?php

namespace App\Modules\Learning\Controllers;

use App\Controllers\BaseController;
use App\Modules\Learning\Services\LearningService;
use App\Modules\Cms\Services\MediaLibraryService;

class LearningCmsController extends BaseController
{
    protected LearningService $learningService;
    protected MediaLibraryService $mediaService;
    protected $db;

    public function __construct()
    {
        $this->learningService = new LearningService();
        $this->mediaService    = new MediaLibraryService();
        $this->db              = \Config\Database::connect();
    }

    public function index()
    {
        $filters = [
            'search'      => $this->request->getGet('search'),
            'division_id' => $this->request->getGet('division_id'),
            'category'    => $this->request->getGet('category'),
            'status'      => $this->request->getGet('status') ?: 'all',
        ];

        $materials = $this->learningService->getAdminMaterials($filters);
        $divisions = $this->db->table('divisions')->where('status', 'active')->get()->getResultArray();
        $mediaList = $this->mediaService->getAllMedia();

        return view('App\Modules\Learning\Views\cms\index', [
            'title'     => 'Kelola Materi Pembelajaran - Admin CMS',
            'materials' => $materials,
            'divisions' => $divisions,
            'filters'   => $filters,
            'mediaList' => $mediaList,
        ]);
    }

    public function create()
    {
        $this->checkWritePermission();

        $divisions = $this->db->table('divisions')->where('status', 'active')->get()->getResultArray();
        $mediaList = $this->mediaService->getAllMedia();

        return view('App\Modules\Learning\Views\cms\form', [
            'title'     => 'Tambah Materi Pembelajaran Baru - Admin CMS',
            'material'  => null,
            'divisions' => $divisions,
            'mediaList' => $mediaList,
        ]);
    }

    public function store()
    {
        $this->checkWritePermission();

        $authorId = (int)session()->get('user_id');
        $data     = $this->request->getPost();

        // Handle thumbnail file upload if provided directly
        $thumbnailFile = $this->request->getFile('thumbnail_file');
        if ($thumbnailFile && $thumbnailFile->isValid() && !$thumbnailFile->hasMoved()) {
            $newName = $thumbnailFile->getRandomName();
            $targetDir = ROOTPATH . 'public/uploads/learning';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $thumbnailFile->move($targetDir, $newName);
            $data['thumbnail'] = 'uploads/learning/' . $newName;
        }

        // Handle banner file upload if provided directly
        $bannerFile = $this->request->getFile('banner_file');
        if ($bannerFile && $bannerFile->isValid() && !$bannerFile->hasMoved()) {
            $newName = $bannerFile->getRandomName();
            $targetDir = ROOTPATH . 'public/uploads/learning';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $bannerFile->move($targetDir, $newName);
            $data['banner'] = 'uploads/learning/' . $newName;
        }

        $result = $this->learningService->storeMaterial($data, $authorId);
        $res    = $result['body'] ?? $result;

        if (($res['status'] ?? '') === 'success') {
            return redirect()->to('/admin/learning')->with('success', $res['message'] ?? 'Materi berhasil disimpan.');
        }

        return redirect()->back()->withInput()->with('error', $res['message'] ?? 'Gagal menyimpan materi.');
    }

    public function edit(int $id)
    {
        $material = $this->db->table('learning_materials')->where('id', $id)->get()->getRowArray();
        if (!$material) {
            return redirect()->to('/admin/learning')->with('error', 'Materi tidak ditemukan.');
        }

        $material['tags'] = $this->learningService->getTagsForMaterial($id);
        $divisions        = $this->db->table('divisions')->where('status', 'active')->get()->getResultArray();
        $mediaList        = $this->mediaService->getAllMedia();

        return view('App\Modules\Learning\Views\cms\form', [
            'title'     => 'Edit Materi Pembelajaran - Admin CMS',
            'material'  => $material,
            'divisions' => $divisions,
            'mediaList' => $mediaList,
        ]);
    }

    public function update(int $id)
    {
        $this->checkWritePermission();

        $editorId = (int)session()->get('user_id');
        $data     = $this->request->getPost();

        // Handle thumbnail file upload
        $thumbnailFile = $this->request->getFile('thumbnail_file');
        if ($thumbnailFile && $thumbnailFile->isValid() && !$thumbnailFile->hasMoved()) {
            $newName = $thumbnailFile->getRandomName();
            $targetDir = ROOTPATH . 'public/uploads/learning';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $thumbnailFile->move($targetDir, $newName);
            $data['thumbnail'] = 'uploads/learning/' . $newName;
        }

        // Handle banner file upload
        $bannerFile = $this->request->getFile('banner_file');
        if ($bannerFile && $bannerFile->isValid() && !$bannerFile->hasMoved()) {
            $newName = $bannerFile->getRandomName();
            $targetDir = ROOTPATH . 'public/uploads/learning';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $bannerFile->move($targetDir, $newName);
            $data['banner'] = 'uploads/learning/' . $newName;
        }

        $result = $this->learningService->updateMaterial($id, $data, $editorId);
        $res    = $result['body'] ?? $result;

        if (($res['status'] ?? '') === 'success') {
            return redirect()->to('/admin/learning')->with('success', $res['message'] ?? 'Materi berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', $res['message'] ?? 'Gagal memperbarui materi.');
    }

    public function delete(int $id)
    {
        $this->checkWritePermission();

        $actorId   = (int)session()->get('user_id');
        $result    = $this->learningService->softDeleteMaterial($id, $actorId);
        $res       = $result['body'] ?? $result;
        $statusKey = ($res['status'] ?? '') === 'success' ? 'success' : 'error';

        return redirect()->to('/admin/learning')->with($statusKey, $res['message'] ?? '');
    }

    public function restore(int $id)
    {
        $this->checkWritePermission();

        $actorId   = (int)session()->get('user_id');
        $result    = $this->learningService->restoreMaterial($id, $actorId);
        $res       = $result['body'] ?? $result;
        $statusKey = ($res['status'] ?? '') === 'success' ? 'success' : 'error';

        return redirect()->to('/admin/learning')->with($statusKey, $res['message'] ?? '');
    }

    public function purge(int $id)
    {
        $this->checkWritePermission();

        $actorId   = (int)session()->get('user_id');
        $result    = $this->learningService->purgeMaterial($id, $actorId);
        $res       = $result['body'] ?? $result;
        $statusKey = ($res['status'] ?? '') === 'success' ? 'success' : 'error';

        return redirect()->to('/admin/learning?status=trash')->with($statusKey, $res['message'] ?? '');
    }

    public function bulkAction()
    {
        $this->checkWritePermission();

        $actorId   = (int)session()->get('user_id');
        $action    = $this->request->getPost('bulk_action');
        $ids       = $this->request->getPost('selected_ids') ?: [];

        $result    = $this->learningService->bulkAction($action, $ids, $actorId);
        $res       = $result['body'] ?? $result;
        $statusKey = ($res['status'] ?? '') === 'success' ? 'success' : 'error';

        return redirect()->to('/admin/learning')->with($statusKey, $res['message'] ?? '');
    }

    private function checkWritePermission()
    {
        $roleSlug = session()->get('role_slug');
        if ($roleSlug !== 'superadmin') {
            redirect()->to('/admin/learning')->with('error', 'Hanya Super Admin yang memiliki hak untuk menambah/mengubah/menghapus materi.')->send();
            exit;
        }
    }
}
