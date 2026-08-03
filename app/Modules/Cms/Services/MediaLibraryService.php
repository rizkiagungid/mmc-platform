<?php

namespace App\Modules\Cms\Services;

use App\Services\BaseService;

class MediaLibraryService extends BaseService
{
    public function getAllMedia(?string $folder = null, ?string $search = null): array
    {
        $builder = $this->db->table('media_library')
                           ->select('media_library.*, users.full_name as uploader_name')
                           ->join('users', 'users.id = media_library.uploaded_by', 'left');

        if ($folder) {
            $builder->where('media_library.folder', $folder);
        }

        if ($search) {
            $builder->groupStart()
                    ->like('media_library.original_name', $search)
                    ->orLike('media_library.alt_text', $search)
                    ->orLike('media_library.caption', $search)
                    ->groupEnd();
        }

        return $builder->orderBy('media_library.created_at', 'DESC')->get()->getResultArray();
    }

    public function uploadMedia($file, int $uploaderId, ?string $folder = 'general', ?string $alt = null, ?string $caption = null): array
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->error('File tidak valid atau telah dipindahkan.');
        }

        $originalName = $file->getClientName();
        $mimeType     = $file->getClientMimeType();
        $extension    = $file->getClientExtension();
        $fileSize     = $file->getSize();
        $newName      = $file->getRandomName();

        $targetFolder = ROOTPATH . 'public/uploads/cms/' . $folder;
        if (!is_dir($targetFolder)) {
            mkdir($targetFolder, 0755, true);
        }

        $file->move($targetFolder, $newName);
        $publicPath = base_url('uploads/cms/' . $folder . '/' . $newName);

        // Get Image Dimensions if Image
        $width  = null;
        $height = null;
        $fullPath = $targetFolder . '/' . $newName;
        if (strpos($mimeType, 'image') !== false && file_exists($fullPath)) {
            $imgSize = @getimagesize($fullPath);
            if ($imgSize) {
                $width  = $imgSize[0];
                $height = $imgSize[1];
            }
        }

        $fileHash = file_exists($fullPath) ? md5_file($fullPath) : null;

        $this->db->table('media_library')->insert([
            'filename'      => $newName,
            'original_name' => $originalName,
            'file_path'     => $publicPath,
            'mime_type'     => $mimeType,
            'extension'     => strtolower($extension),
            'file_size'     => $fileSize,
            'width'         => $width,
            'height'        => $height,
            'uploaded_by'   => $uploaderId,
            'alt_text'      => $alt ?: $originalName,
            'caption'       => $caption,
            'folder'        => $folder,
            'hash'          => $fileHash,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        $mediaId = $this->db->insertID();

        return $this->success('File berhasil diunggah ke Media Library.', [
            'id'        => $mediaId,
            'url'       => $publicPath,
            'filename'  => $originalName,
            'mime_type' => $mimeType,
        ]);
    }

    public function deleteMedia(int $id): array
    {
        $media = $this->db->table('media_library')->where('id', $id)->get()->getRowArray();
        if (!$media) {
            return $this->error('File tidak ditemukan.');
        }

        // Delete physical file
        $relativePath = str_replace(base_url(), '', $media['file_path']);
        $filePath = ROOTPATH . 'public/' . ltrim($relativePath, '/');
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $this->db->table('media_library')->where('id', $id)->delete();
        return $this->success('File berhasil dihapus dari Media Library.');
    }
}
