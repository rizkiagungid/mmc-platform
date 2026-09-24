<?php

namespace App\Modules\Cms\Controllers;

use App\Controllers\BaseController;

class GalleryCmsController extends BaseController
{
    private function ensureColumnsExist($db)
    {
        $albumFields = [
            'category'            => ['type' => 'VARCHAR', 'constraint' => '100', 'default' => 'Kegiatan'],
            'event_date'          => ['type' => 'DATE', 'null' => true],
            'external_link'       => ['type' => 'VARCHAR', 'constraint' => '500', 'null' => true],
            'external_link_title' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'media_files'         => ['type' => 'LONGTEXT', 'null' => true],
            'created_by'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
        ];

        $forge = \Config\Database::forge();
        foreach ($albumFields as $col => $def) {
            if (!$db->fieldExists($col, 'gallery_albums')) {
                $forge->addColumn('gallery_albums', [$col => $def]);
            }
        }

        if ($db->tableExists('gallery_photos') && !$db->fieldExists('media_type', 'gallery_photos')) {
            $forge->addColumn('gallery_photos', [
                'media_type' => ['type' => 'VARCHAR', 'constraint' => '20', 'default' => 'image'],
            ]);
        }
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $this->ensureColumnsExist($db);

        $albums = $db->table('gallery_albums')
            ->select('gallery_albums.*, users.full_name as author_name, users.avatar as author_avatar')
            ->join('users', 'users.id = gallery_albums.created_by', 'left')
            ->orderBy('gallery_albums.event_date', 'DESC')
            ->orderBy('gallery_albums.id', 'DESC')
            ->get()->getResultArray();

        $totalPhotos = 0;
        $totalVideos = 0;

        foreach ($albums as &$album) {
            $mediaList = !empty($album['media_files']) ? json_decode($album['media_files'], true) : [];
            if (!is_array($mediaList)) {
                $mediaList = [];
            }
            $album['media_list'] = $mediaList;

            $photosInAlbum = 0;
            $videosInAlbum = 0;
            foreach ($mediaList as $m) {
                if (($m['type'] ?? 'image') === 'video') {
                    $videosInAlbum++;
                    $totalVideos++;
                } else {
                    $photosInAlbum++;
                    $totalPhotos++;
                }
            }
            $album['photo_count'] = $photosInAlbum;
            $album['video_count'] = $videosInAlbum;
            $album['total_media'] = count($mediaList);
        }

        return view('App\Modules\Cms\Views\gallery\index', [
            'title'       => 'Manajemen Galeri & Dokumentasi Kegiatan',
            'albums'      => $albums,
            'totalAlbums' => count($albums),
            'totalPhotos' => $totalPhotos,
            'totalVideos' => $totalVideos,
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $this->ensureColumnsExist($db);

        $title             = trim($this->request->getPost('title') ?? '');
        $category          = trim($this->request->getPost('category') ?: 'Dokumentasi');
        $eventDate         = $this->request->getPost('event_date') ?: date('Y-m-d');
        $description       = trim($this->request->getPost('description') ?? '');
        $externalLink      = trim($this->request->getPost('external_link') ?? '');
        $externalLinkTitle = trim($this->request->getPost('external_link_title') ?? '');

        if (empty($title)) {
            return redirect()->back()->with('error', 'Judul kegiatan atau album wajib diisi.')->withInput();
        }

        if (!empty($externalLink) && empty($externalLinkTitle)) {
            $externalLinkTitle = 'Link Dokumentasi / Video';
        }

        // Generate unique slug
        $baseSlug = url_title($title, '-', true);
        if (empty($baseSlug)) {
            $baseSlug = 'kegiatan-' . time();
        }
        $slug = $baseSlug;
        $counter = 1;
        while ($db->table('gallery_albums')->where('slug', $slug)->countAllResults() > 0) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $uploadDir = FCPATH . 'uploads/gallery';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        // Handle Cover Image
        $coverImage = null;
        $coverFile = $this->request->getFile('cover_image_file');
        if ($coverFile && $coverFile->isValid() && !$coverFile->hasMoved()) {
            $coverName = $coverFile->getRandomName();
            $coverFile->move($uploadDir, $coverName);
            $coverImage = 'uploads/gallery/' . $coverName;
        }

        // Handle Multiple Media Files (Photos & Videos)
        $mediaList = [];
        $files = $this->request->getFileMultiple('media_files');
        if ($files) {
            foreach ($files as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $mime = $file->getClientMimeType();
                    $ext = strtolower($file->getClientExtension());
                    $isVideo = (strpos($mime, 'video/') === 0) || in_array($ext, ['mp4', 'webm', 'mov', 'ogg', 'mkv', '3gp']);

                    $fileName = $file->getRandomName();
                    $file->move($uploadDir, $fileName);
                    $relPath = 'uploads/gallery/' . $fileName;

                    $mediaItem = [
                        'url'          => $relPath,
                        'type'         => $isVideo ? 'video' : 'image',
                        'name'         => $file->getClientName(),
                        'size'         => $file->getSize(),
                        'mime'         => $mime,
                        'uploaded_at'  => date('Y-m-d H:i:s'),
                    ];
                    $mediaList[] = $mediaItem;

                    // Fallback cover if no cover was uploaded
                    if (!$coverImage && !$isVideo) {
                        $coverImage = $relPath;
                    }
                }
            }
        }

        // If still no cover image and we have media, take the first one
        if (!$coverImage && !empty($mediaList)) {
            $coverImage = $mediaList[0]['url'];
        }

        $userId = session()->get('user_id') ?: null;

        $db->transStart();

        $albumData = [
            'title'               => $title,
            'category'            => $category,
            'event_date'          => $eventDate,
            'slug'                => $slug,
            'cover_image'         => $coverImage,
            'media_files'         => !empty($mediaList) ? json_encode($mediaList) : null,
            'description'         => $description,
            'external_link'       => $externalLink ?: null,
            'external_link_title' => $externalLinkTitle ?: null,
            'created_by'          => $userId,
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ];

        $db->table('gallery_albums')->insert($albumData);
        $albumId = $db->insertID();

        // Also populate gallery_photos for compatibility
        foreach ($mediaList as $item) {
            $db->table('gallery_photos')->insert([
                'album_id'    => $albumId,
                'image_url'   => $item['url'],
                'media_type'  => $item['type'],
                'title'       => $item['name'] ?? $title,
                'caption'     => null,
                'is_featured' => 0,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        $db->transComplete();

        return redirect()->to('/admin/cms/gallery')->with('success', 'Dokumentasi kegiatan berhasil ditambahkan ke galeri.');
    }

    public function update(int $id)
    {
        $db = \Config\Database::connect();
        $this->ensureColumnsExist($db);

        $existing = $db->table('gallery_albums')->where('id', $id)->get()->getRowArray();
        if (!$existing) {
            return redirect()->back()->with('error', 'Album atau kegiatan galeri tidak ditemukan.');
        }

        $title             = trim($this->request->getPost('title') ?? '');
        $category          = trim($this->request->getPost('category') ?: 'Dokumentasi');
        $eventDate         = $this->request->getPost('event_date') ?: ($existing['event_date'] ?? date('Y-m-d'));
        $description       = trim($this->request->getPost('description') ?? '');
        $externalLink      = trim($this->request->getPost('external_link') ?? '');
        $externalLinkTitle = trim($this->request->getPost('external_link_title') ?? '');

        if (empty($title)) {
            return redirect()->back()->with('error', 'Judul kegiatan atau album wajib diisi.');
        }

        if (!empty($externalLink) && empty($externalLinkTitle)) {
            $externalLinkTitle = 'Link Dokumentasi / Video';
        }

        $uploadDir = FCPATH . 'uploads/gallery';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        // Handle Cover Image Replacement
        $coverImage = $existing['cover_image'];
        $coverFile = $this->request->getFile('cover_image_file');
        if ($coverFile && $coverFile->isValid() && !$coverFile->hasMoved()) {
            $coverName = $coverFile->getRandomName();
            $coverFile->move($uploadDir, $coverName);
            $newCoverPath = 'uploads/gallery/' . $coverName;

            // Remove previous cover if it wasn't one of the media files
            if ($coverImage && $coverImage !== $newCoverPath) {
                // Delete only if not part of mediaList
                $this->deleteLocalAsset($coverImage);
            }
            $coverImage = $newCoverPath;
        }

        // Load existing media files
        $mediaList = !empty($existing['media_files']) ? json_decode($existing['media_files'], true) : [];
        if (!is_array($mediaList)) {
            $mediaList = [];
        }

        // Handle Additional Media Files
        $newFiles = $this->request->getFileMultiple('media_files');
        $newAddedList = [];
        if ($newFiles) {
            foreach ($newFiles as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $mime = $file->getClientMimeType();
                    $ext = strtolower($file->getClientExtension());
                    $isVideo = (strpos($mime, 'video/') === 0) || in_array($ext, ['mp4', 'webm', 'mov', 'ogg', 'mkv', '3gp']);

                    $fileName = $file->getRandomName();
                    $file->move($uploadDir, $fileName);
                    $relPath = 'uploads/gallery/' . $fileName;

                    $mediaItem = [
                        'url'          => $relPath,
                        'type'         => $isVideo ? 'video' : 'image',
                        'name'         => $file->getClientName(),
                        'size'         => $file->getSize(),
                        'mime'         => $mime,
                        'uploaded_at'  => date('Y-m-d H:i:s'),
                    ];
                    $mediaList[] = $mediaItem;
                    $newAddedList[] = $mediaItem;

                    if (!$coverImage && !$isVideo) {
                        $coverImage = $relPath;
                    }
                }
            }
        }

        if (!$coverImage && !empty($mediaList)) {
            $coverImage = $mediaList[0]['url'];
        }

        $db->transStart();

        $db->table('gallery_albums')->where('id', $id)->update([
            'title'               => $title,
            'category'            => $category,
            'event_date'          => $eventDate,
            'cover_image'         => $coverImage,
            'media_files'         => !empty($mediaList) ? json_encode(array_values($mediaList)) : null,
            'description'         => $description,
            'external_link'       => $externalLink ?: null,
            'external_link_title' => $externalLinkTitle ?: null,
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

        // Insert new media items into gallery_photos
        foreach ($newAddedList as $item) {
            $db->table('gallery_photos')->insert([
                'album_id'    => $id,
                'image_url'   => $item['url'],
                'media_type'  => $item['type'],
                'title'       => $item['name'] ?? $title,
                'caption'     => null,
                'is_featured' => 0,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        $db->transComplete();

        return redirect()->to('/admin/cms/gallery')->with('success', 'Data kegiatan galeri berhasil diperbarui.');
    }

    public function deleteMedia(int $id)
    {
        $db = \Config\Database::connect();
        $mediaUrl = trim($this->request->getPost('media_url') ?? '');

        if (empty($mediaUrl)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Media URL tidak valid.']);
        }

        $album = $db->table('gallery_albums')->where('id', $id)->get()->getRowArray();
        if (!$album) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Album tidak ditemukan.']);
        }

        $mediaList = !empty($album['media_files']) ? json_decode($album['media_files'], true) : [];
        if (!is_array($mediaList)) {
            $mediaList = [];
        }

        $updatedList = [];
        $found = false;
        foreach ($mediaList as $item) {
            if ($item['url'] === $mediaUrl) {
                $found = true;
                $this->deleteLocalAsset($item['url']);
            } else {
                $updatedList[] = $item;
            }
        }

        if ($found) {
            $db->transStart();

            // Update album media_files
            $newCover = $album['cover_image'];
            if ($newCover === $mediaUrl) {
                // If deleted item was cover, assign another image or first item
                $newCover = !empty($updatedList) ? $updatedList[0]['url'] : null;
            }

            $db->table('gallery_albums')->where('id', $id)->update([
                'media_files' => !empty($updatedList) ? json_encode(array_values($updatedList)) : null,
                'cover_image' => $newCover,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);

            // Remove from gallery_photos
            $db->table('gallery_photos')->where('album_id', $id)->where('image_url', $mediaUrl)->delete();

            $db->transComplete();

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'File media berhasil dihapus dari galeri.',
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak ditemukan di album ini.']);
    }

    public function delete(int $id)
    {
        $db = \Config\Database::connect();
        $album = $db->table('gallery_albums')->where('id', $id)->get()->getRowArray();

        if ($album) {
            // Delete cover
            $this->deleteLocalAsset($album['cover_image'] ?? null);

            // Delete all media files
            if (!empty($album['media_files'])) {
                $mediaList = json_decode($album['media_files'], true);
                if (is_array($mediaList)) {
                    foreach ($mediaList as $item) {
                        $this->deleteLocalAsset($item['url'] ?? null);
                    }
                }
            }

            // Delete gallery_photos records
            $db->table('gallery_photos')->where('album_id', $id)->delete();

            // Delete album record
            $db->table('gallery_albums')->where('id', $id)->delete();
        }

        return redirect()->to('/admin/cms/gallery')->with('success', 'Kegiatan dan seluruh file dokumentasi terkait berhasil dihapus.');
    }

    private function deleteLocalAsset(?string $relativePath)
    {
        if (empty($relativePath) || strpos($relativePath, 'http://') === 0 || strpos($relativePath, 'https://') === 0) {
            return;
        }

        $fullPath = FCPATH . ltrim($relativePath, '/\\');
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
