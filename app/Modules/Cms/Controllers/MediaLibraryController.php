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
        $folder = $this->request->getGet('folder');
        $search = $this->request->getGet('search');

        $media = $this->mediaService->getAllMedia($folder, $search);

        return view('App\Modules\Cms\Views\media_library\index', [
            'title' => 'Centralized Media Library',
            'media' => $media,
        ]);
    }

    public function upload()
    {
        $file    = $this->request->getFile('media_file');
        $folder  = $this->request->getPost('folder') ?: 'general';
        $alt     = $this->request->getPost('alt_text');
        $caption = $this->request->getPost('caption');

        $result = $this->mediaService->uploadMedia($file, session()->get('user_id'), $folder, $alt, $caption);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result['body']);
        }

        if ($result['body']['status'] !== 'success') {
            return redirect()->back()->with('error', $result['body']['message']);
        }

        return redirect()->back()->with('success', $result['body']['message']);
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
