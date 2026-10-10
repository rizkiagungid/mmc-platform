<?php

namespace App\Modules\Cms\Controllers;

use App\Controllers\BaseController;
use App\Modules\Cms\Services\MediaLibraryService;

class MediaLibraryController extends BaseController
{
    protected $mediaService;

    public function __construct()
    {
        $this->mediaService = new MediaLibraryService();
    }

    public function index()
    {
        $folder = $this->request->getGet('folder') ?: 'all';
        $search = $this->request->getGet('search') ?: '';

        $media = $this->mediaService->getAllMedia($folder, $search);

        return view('App\Modules\Cms\Views\media_library\index', [
            'title'          => 'Centralized Media Library',
            'media'          => $media,
            'selectedFolder' => $folder,
            'searchQuery'    => $search,
        ]);
    }

    public function upload()
    {
        @ini_set('upload_max_filesize', '100M');
        @ini_set('post_max_size', '120M');

        try {
            $folder  = trim((string)($this->request->getPost('folder') ?: 'general'));
            $alt     = trim((string)($this->request->getPost('alt_text') ?: ''));
            $caption = trim((string)($this->request->getPost('caption') ?: ''));
            $userId  = (int) (session()->get('user_id') ?? session()->get('id') ?? 1);

            // Collect files (supports multi-upload media_files[] and single media_file)
            $files = $this->request->getFileMultiple('media_files');
            if (empty($files)) {
                $single = $this->request->getFile('media_file');
                if ($single && $single->getError() !== UPLOAD_ERR_NO_FILE) {
                    $files = [$single];
                }
            }

            // Filter out empty file slots
            $validFiles = [];
            if (!empty($files)) {
                foreach ($files as $f) {
                    if ($f && $f->getError() !== UPLOAD_ERR_NO_FILE) {
                        $validFiles[] = $f;
                    }
                }
            }

            if (empty($validFiles)) {
                $msg = 'Silakan pilih setidaknya satu berkas yang ingin diunggah.';
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['status' => 'error', 'message' => $msg]);
                }
                return redirect()->back()->with('error', $msg);
            }

            $uploadedCount = 0;
            $failedCount   = 0;
            $uploadedData  = [];
            $errorMessages = [];

            foreach ($validFiles as $file) {
                if (!$file->isValid()) {
                    $failedCount++;
                    $errorMessages[] = $file->getClientName() . ': ' . $file->getErrorString();
                    continue;
                }

                $result = $this->mediaService->uploadMedia($file, $userId, $folder, $alt, $caption);
                if ($result['body']['status'] === 'success') {
                    $uploadedCount++;
                    $uploadedData[] = $result['body']['data'];
                } else {
                    $failedCount++;
                    $errorMessages[] = $file->getClientName() . ': ' . $result['body']['message'];
                }
            }

            if ($uploadedCount === 0) {
                $msg = 'Gagal mengunggah berkas: ' . implode(', ', $errorMessages);
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['status' => 'error', 'message' => $msg]);
                }
                return redirect()->back()->with('error', $msg);
            }

            $successMsg = ($uploadedCount > 1) 
                ? "Berhasil mengunggah {$uploadedCount} berkas ke Media Library."
                : 'Berkas berhasil diunggah ke Media Library.';

            if ($failedCount > 0) {
                $successMsg .= " ({$failedCount} berkas gagal: " . implode(', ', $errorMessages) . ')';
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => $successMsg,
                    'count'   => $uploadedCount,
                    'data'    => $uploadedData,
                ]);
            }

            return redirect()->back()->with('success', $successMsg);
        } catch (\Throwable $e) {
            log_message('error', 'Media upload error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            $msg = 'Terjadi kendala saat mengunggah berkas: ' . $e->getMessage();
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => $msg]);
            }
            return redirect()->back()->with('error', $msg);
        }
    }

    public function delete(int $id)
    {
        $result = $this->mediaService->deleteMedia($id);
        return redirect()->back()->with('success', $result['body']['message']);
    }

    public function apiList()
    {
        $media = $this->mediaService->getAllMedia();
        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $media,
        ]);
    }
}
