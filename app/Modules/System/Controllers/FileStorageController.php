<?php

namespace App\Modules\System\Controllers;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Modules\System\Services\FileStorageService;

class FileStorageController extends BaseController
{
    protected FileStorageService $storageService;
    protected AuditLogModel $auditLogModel;

    public function __construct()
    {
        $this->storageService = new FileStorageService();
        $this->auditLogModel   = new AuditLogModel();
    }

    /**
     * Display storage Overview & File Manager for Superadmin
     */
    public function index()
    {
        // Enforce Superadmin access check
        if (session()->get('role_slug') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Fitur ini hanya untuk Superadmin.');
        }

        $selectedCategory = $this->request->getGet('category') ?? 'all';

        $overview = $this->storageService->getStorageOverview();
        $files = $this->storageService->getFilesList($selectedCategory);

        $data = [
            'title'            => 'Manajemen Asset & Storage Disk Server',
            'overview'         => $overview,
            'files'            => $files,
            'selectedCategory' => $selectedCategory,
        ];

        return view('App\Modules\System\Views\storage\index', $data);
    }

    /**
     * Process permanent deletion of a single file
     */
    public function deleteFile()
    {
        if (session()->get('role_slug') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $filePath = $this->request->getPost('file_path');
        if (empty($filePath)) {
            return redirect()->back()->with('error', 'Path file tidak boleh kosong.');
        }

        $result = $this->storageService->deleteFiles([$filePath]);

        if ($result['deleted_count'] > 0) {
            $this->auditLogModel->recordLog(
                session()->get('user_id'),
                'STORAGE_FILE_DELETE',
                "Menghapus 1 berkas unggahan permanen dari disk server: {$filePath}"
            );

            return redirect()->back()->with('success', 'File berhasil dihapus permanen dari disk server.');
        }

        $errorMsg = !empty($result['errors']) ? implode(', ', $result['errors']) : 'Gagal menghapus file.';
        return redirect()->back()->with('error', $errorMsg);
    }

    /**
     * Process bulk permanent deletion of multiple files
     */
    public function bulkDelete()
    {
        if (session()->get('role_slug') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $filePaths = $this->request->getPost('file_paths');
        if (empty($filePaths) || !is_array($filePaths)) {
            return redirect()->back()->with('error', 'Pilih setidaknya satu file untuk dihapus.');
        }

        $result = $this->storageService->deleteFiles($filePaths);

        $this->auditLogModel->recordLog(
            session()->get('user_id'),
            'STORAGE_FILE_BULK_DELETE',
            "Menghapus permanen massal {$result['deleted_count']} berkas dari disk storage server. (Gagal: {$result['failed_count']})"
        );

        $msg = "{$result['deleted_count']} file berhasil dihapus secara permanen.";
        if ($result['failed_count'] > 0) {
            $msg .= " ({$result['failed_count']} file gagal dihapus).";
        }

        return redirect()->back()->with('success', $msg);
    }
}
