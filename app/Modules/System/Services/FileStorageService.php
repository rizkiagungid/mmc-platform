<?php

namespace App\Modules\System\Services;

class FileStorageService
{
    /**
     * Directory path to inspect (defaults to ROOTPATH . 'public/uploads')
     */
    protected string $baseUploadPath;

    public function __construct()
    {
        $this->baseUploadPath = FCPATH . 'uploads';
    }

    /**
     * Format bytes into human readable binary format (KB, MB, GB)
     */
    public function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1024 ** $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Get statistics on disk usage & file count per subfolder
     */
    public function getStorageOverview(): array
    {
        $folders = [];
        $totalSize = 0;
        $totalFiles = 0;

        if (!is_dir($this->baseUploadPath)) {
            return [
                'total_size'      => 0,
                'total_size_fmt'  => '0 B',
                'total_files'     => 0,
                'categories'      => [],
            ];
        }

        $items = array_diff(scandir($this->baseUploadPath), ['.', '..']);
        foreach ($items as $item) {
            $itemPath = $this->baseUploadPath . DIRECTORY_SEPARATOR . $item;
            if (is_dir($itemPath)) {
                $dirStats = $this->getDirectoryStats($itemPath);
                $folders[$item] = [
                    'name'           => $item,
                    'file_count'     => $dirStats['file_count'],
                    'size_bytes'     => $dirStats['size_bytes'],
                    'size_formatted' => $this->formatBytes($dirStats['size_bytes']),
                ];
                $totalSize += $dirStats['size_bytes'];
                $totalFiles += $dirStats['file_count'];
            }
        }

        return [
            'total_size'     => $totalSize,
            'total_size_fmt' => $this->formatBytes($totalSize),
            'total_files'    => $totalFiles,
            'categories'     => $folders,
        ];
    }

    /**
     * Helper to recursively sum directory size and count files
     */
    protected function getDirectoryStats(string $dirPath): array
    {
        $size = 0;
        $count = 0;

        if (!is_dir($dirPath)) {
            return ['size_bytes' => 0, 'file_count' => 0];
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dirPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $filename = strtolower($file->getFilename());
                // Ignore system placeholder & security files
                if (in_array($filename, ['index.html', 'index.htm', '.gitkeep', '.htaccess'])) {
                    continue;
                }
                $size += $file->getSize();
                $count++;
            }
        }

        return [
            'size_bytes' => $size,
            'file_count' => $count,
        ];
    }

    /**
     * List files filtered by subfolder (or all)
     */
    public function getFilesList(?string $subfolder = null): array
    {
        $targetDir = $this->baseUploadPath;
        if (!empty($subfolder) && $subfolder !== 'all') {
            // Sanitize subfolder path to prevent directory traversal
            $cleanSubfolder = str_replace(['..', '/', '\\'], '', $subfolder);
            $targetDir = $this->baseUploadPath . DIRECTORY_SEPARATOR . $cleanSubfolder;
        }

        if (!is_dir($targetDir)) {
            return [];
        }

        $filesData = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($targetDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                $fileName = $fileInfo->getFilename();
                // Exclude system security files from list
                if (in_array(strtolower($fileName), ['index.html', 'index.htm', '.gitkeep', '.htaccess'])) {
                    continue;
                }

                $realPath = $fileInfo->getRealPath();
                $relativePath = str_replace(FCPATH, '', $realPath);
                $relativePath = str_replace('\\', '/', $relativePath);

                $fileName = $fileInfo->getFilename();
                $extension = strtolower($fileInfo->getExtension());
                $size = $fileInfo->getSize();
                $mtime = $fileInfo->getMTime();

                // Determine category from relative path e.g. uploads/avatars/foo.jpg -> avatars
                $pathParts = explode('/', $relativePath);
                $category = (count($pathParts) >= 3 && $pathParts[0] === 'uploads') ? $pathParts[1] : 'root';

                $filesData[] = [
                    'relative_path'  => $relativePath,
                    'file_name'      => $fileName,
                    'category'       => $category,
                    'extension'      => $extension,
                    'size_bytes'     => $size,
                    'size_formatted' => $this->formatBytes($size),
                    'modified_at'    => date('Y-m-d H:i:s', $mtime),
                    'url'            => base_url($relativePath),
                    'is_image'       => in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']),
                ];
            }
        }

        // Sort by modified date descending
        usort($filesData, fn($a, $b) => strcmp($b['modified_at'], $a['modified_at']));

        return $filesData;
    }

    /**
     * Permanently delete a single file or multiple files
     */
    public function deleteFiles(array $relativePaths): array
    {
        $deletedCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($relativePaths as $relPath) {
            // Security check: path must begin with uploads/ and not contain '..'
            $relPathClean = ltrim(str_replace('\\', '/', $relPath), '/');
            if (strpos($relPathClean, 'uploads/') !== 0 || strpos($relPathClean, '..') !== false) {
                $failedCount++;
                $errors[] = "Akses ditolak untuk path: {$relPath}";
                continue;
            }

            $fullPath = FCPATH . $relPathClean;
            if (file_exists($fullPath) && is_file($fullPath)) {
                if (@unlink($fullPath)) {
                    $deletedCount++;
                } else {
                    $failedCount++;
                    $errors[] = "Gagal menghapus file: {$relPathClean}";
                }
            } else {
                $failedCount++;
                $errors[] = "File tidak ditemukan: {$relPathClean}";
            }
        }

        return [
            'deleted_count' => $deletedCount,
            'failed_count'  => $failedCount,
            'errors'        => $errors,
        ];
    }
}
