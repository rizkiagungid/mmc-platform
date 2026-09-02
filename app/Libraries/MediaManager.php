<?php

namespace App\Libraries;

/**
 * Universal Media Manager
 * 
 * Handles path normalization, dynamic multi-environment URL resolution,
 * file category classification, and dev proxy / fallback handling.
 */
class MediaManager
{
    /**
     * File extensions mapped to categories
     */
    protected static array $categories = [
        'image' => [
            'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico', 'avif', 'tiff', 'heic'],
            'icon'       => 'fa-solid fa-file-image text-info',
            'label'      => 'Gambar',
            'color'      => '#0ea5e9',
        ],
        'video' => [
            'extensions' => ['mp4', 'webm', 'mov', 'mkv', 'avi', 'wmv', 'flv', 'm4v', '3gp', 'ts'],
            'icon'       => 'fa-solid fa-file-video text-primary',
            'label'      => 'Video',
            'color'      => '#3b82f6',
        ],
        'audio' => [
            'extensions' => ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac', 'wma', 'opus'],
            'icon'       => 'fa-solid fa-file-audio text-purple',
            'label'      => 'Audio',
            'color'      => '#8b5cf6',
        ],
        'document' => [
            'extensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf', 'csv', 'odt', 'ods', 'odp'],
            'icon'       => 'fa-solid fa-file-pdf text-danger',
            'label'      => 'Dokumen',
            'color'      => '#ef4444',
        ],
        'archive' => [
            'extensions' => ['zip', 'rar', '7z', 'tar', 'gz', 'bz2', 'iso', 'xz', 'dmg'],
            'icon'       => 'fa-solid fa-file-zipper text-warning',
            'label'      => 'Arsip Berkas',
            'color'      => '#f59e0b',
        ],
    ];

    /**
     * Clean and normalize any input path / URL into a clean relative path (e.g. "uploads/tasks/filename.ext").
     */
    public static function cleanPath(?string $path): string
    {
        if ($path === null || trim($path) === '') {
            return '';
        }

        $raw = trim($path);

        // If it's a known external cloud link (Drive, YouTube, Canva, Figma, etc.), keep it untouched
        if (self::isExternalUrl($raw) && !self::isAppBaseUrl($raw)) {
            return $raw;
        }

        // Strip any protocol, domain, port, and subpaths
        $parsed = parse_url($raw);
        $pathOnly = $parsed['path'] ?? $raw;

        // Replace backslashes
        $pathOnly = str_replace('\\', '/', $pathOnly);

        // Find "uploads/" in path and retain from "uploads/..." onward
        $uploadsPos = stripos($pathOnly, 'uploads/');
        if ($uploadsPos !== false) {
            return substr($pathOnly, $uploadsPos);
        }

        // Trim leading/trailing slashes
        return ltrim($pathOnly, '/');
    }

    /**
     * Check if a URL points to an external third-party service
     */
    public static function isExternalUrl(?string $url): bool
    {
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
        $currentHost = strtolower(parse_url(base_url(), PHP_URL_HOST) ?? '');

        // If host matches current app host, it's not external
        if ($host === $currentHost || $host === 'localhost' || $host === '127.0.0.1') {
            return false;
        }

        // Production fallback host check
        $prodUrl = env('media.prodFallbackURL', '');
        if ($prodUrl) {
            $prodHost = strtolower(parse_url($prodUrl, PHP_URL_HOST) ?? '');
            if ($host === $prodHost) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if a URL starts with current base_url
     */
    protected static function isAppBaseUrl(string $url): bool
    {
        $base = rtrim(base_url(), '/');
        return strpos($url, $base) === 0;
    }

    /**
     * Resolve full dynamic URL for a media asset based on active environment and local file availability.
     */
    public static function getUrl(?string $path, ?string $fallback = null): string
    {
        if (empty($path)) {
            return $fallback ?? base_url('media/placeholder?type=empty');
        }

        $clean = self::cleanPath($path);

        // If it's an external cloud link, return as is
        if (self::isExternalUrl($clean)) {
            return $clean;
        }

        if (empty($clean)) {
            return $fallback ?? base_url('media/placeholder?type=empty');
        }

        // Physical path check on server
        $localPhysicalPath = FCPATH . ltrim($clean, '/');
        $fileExistsLocally = file_exists($localPhysicalPath) && is_file($localPhysicalPath);

        if ($fileExistsLocally) {
            return base_url($clean);
        }

        // In Development/Local environment: check for Production Fallback / Dev Proxy
        $isDev = (env('CI_ENVIRONMENT', 'development') === 'development');
        $prodFallbackUrl = rtrim(env('media.prodFallbackURL', ''), '/');
        $devProxyEnabled = filter_var(env('media.devProxyEnabled', true), FILTER_VALIDATE_BOOLEAN);

        if ($isDev && !empty($prodFallbackUrl)) {
            if ($devProxyEnabled) {
                // Route through local stream proxy to auto-download/cache or pipe from production
                return base_url('media/proxy?path=' . urlencode($clean));
            }

            // Direct link to production asset
            return $prodFallbackUrl . '/' . ltrim($clean, '/');
        }

        // If file is not found locally and no prod fallback, return local base_url (or placeholder fallback for images)
        $type = self::getType($clean);
        if ($fallback) {
            return $fallback;
        }

        if ($type === 'image') {
            return base_url('media/placeholder?path=' . urlencode($clean));
        }

        return base_url($clean);
    }

    /**
     * Resolve Avatar URL with automatic fallback to initial avatar if missing or empty
     */
    public static function getAvatarUrl(?string $avatar, string $name = 'User'): string
    {
        $defaultFallback = base_url('media/avatar?name=' . urlencode($name));

        if (empty($avatar)) {
            return $defaultFallback;
        }

        $clean = self::cleanPath($avatar);
        if (empty($clean)) {
            return $defaultFallback;
        }

        if (self::isExternalUrl($clean)) {
            return $clean;
        }

        $localPhysicalPath = FCPATH . ltrim($clean, '/');
        if (file_exists($localPhysicalPath) && is_file($localPhysicalPath)) {
            return base_url($clean);
        }

        // In dev with prod fallback:
        $isDev = (env('CI_ENVIRONMENT', 'development') === 'development');
        $prodFallbackUrl = rtrim(env('media.prodFallbackURL', ''), '/');
        if ($isDev && !empty($prodFallbackUrl)) {
            return self::getUrl($clean, $defaultFallback);
        }

        return $defaultFallback;
    }

    /**
     * Get media category (image, video, audio, document, archive, other)
     */
    public static function getType(?string $path): string
    {
        if (empty($path)) {
            return 'other';
        }

        $clean = self::cleanPath($path);
        $fileName = basename(parse_url($clean, PHP_URL_PATH));
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        foreach (self::$categories as $category => $data) {
            if (in_array($ext, $data['extensions'], true)) {
                return $category;
            }
        }

        return 'other';
    }

    /**
     * Get complete metadata info for a file (category, extension, label, icon class, theme color, size).
     */
    public static function getInfo(?string $path): array
    {
        $clean = self::cleanPath($path);
        $type  = self::getType($clean);
        $fileName = basename(parse_url($clean, PHP_URL_PATH));
        $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $catData = self::$categories[$type] ?? [
            'icon'  => 'fa-solid fa-file text-secondary',
            'label' => 'Berkas',
            'color' => '#64748b',
        ];

        // Specific extension overrides
        $icon = $catData['icon'];
        if ($ext === 'pdf') {
            $icon = 'fa-solid fa-file-pdf text-danger';
        } elseif (in_array($ext, ['doc', 'docx'])) {
            $icon = 'fa-solid fa-file-word text-primary';
        } elseif (in_array($ext, ['xls', 'xlsx', 'csv'])) {
            $icon = 'fa-solid fa-file-excel text-success';
        } elseif (in_array($ext, ['ppt', 'pptx'])) {
            $icon = 'fa-solid fa-file-powerpoint text-warning';
        }

        $isExternal = self::isExternalUrl($clean);
        if ($isExternal) {
            $icon = 'fa-solid fa-arrow-up-right-from-square text-info';
            $catData['label'] = 'Tautan Eksternal';
        }

        // File size check if local
        $sizeBytes = null;
        $sizeFormatted = null;
        if (!$isExternal && !empty($clean)) {
            $localPath = FCPATH . ltrim($clean, '/');
            if (file_exists($localPath) && is_file($localPath)) {
                $sizeBytes = filesize($localPath);
                $sizeFormatted = self::formatBytes($sizeBytes);
            }
        }

        return [
            'raw'            => $path,
            'clean_path'     => $clean,
            'url'            => self::getUrl($path),
            'type'           => $type,
            'extension'      => $ext ?: 'link',
            'file_name'      => $fileName ?: $clean,
            'label'          => $catData['label'],
            'icon'           => $icon,
            'color'          => $catData['color'],
            'is_external'    => $isExternal,
            'size_bytes'     => $sizeBytes,
            'size_formatted' => $sizeFormatted,
        ];
    }

    /**
     * Render dynamic HTML element based on media category
     */
    public static function render(?string $path, array $options = []): string
    {
        if (empty($path)) {
            return '<div class="text-secondary small fst-italic">Tidak ada media.</div>';
        }

        $info = self::getInfo($path);
        $type = $info['type'];
        $url  = $info['url'];
        $alt  = esc($options['alt'] ?? $info['file_name']);
        $class = esc($options['class'] ?? '');
        $style = esc($options['style'] ?? '');

        switch ($type) {
            case 'image':
                $fallbackSvg = base_url('media/placeholder?path=' . urlencode($info['clean_path']));
                $imgClass = trim('img-fluid ' . $class);
                return sprintf(
                    '<img src="%s" alt="%s" class="%s" style="%s" loading="lazy" onerror="this.onerror=null; this.src=\'%s\';" />',
                    esc($url),
                    $alt,
                    $imgClass,
                    $style,
                    $fallbackSvg
                );

            case 'video':
                $poster = !empty($options['poster']) ? 'poster="' . esc($options['poster']) . '"' : '';
                $videoClass = trim('w-100 rounded-3 ' . $class);
                return sprintf(
                    '<video src="%s" controls class="%s" %s style="%s"><p class="small text-secondary">Browser Anda tidak mendukung pemutaran video. <a href="%s" target="_blank">Unduh Video</a></p></video>',
                    esc($url),
                    $videoClass,
                    $poster,
                    $style,
                    esc($url)
                );

            case 'audio':
                $audioClass = trim('w-100 ' . $class);
                return sprintf(
                    '<audio src="%s" controls class="%s" style="%s"><p class="small text-secondary">Browser Anda tidak mendukung audio player. <a href="%s" target="_blank">Unduh Audio</a></p></audio>',
                    esc($url),
                    $audioClass,
                    $style,
                    esc($url)
                );

            case 'document':
            case 'archive':
            default:
                // Render sleek interactive file download card / chip
                $icon = $info['icon'];
                $fileName = esc($info['file_name']);
                $subLabel = $info['size_formatted'] ? "{$info['label']} &bull; {$info['size_formatted']}" : $info['label'];

                return sprintf(
                    '<div class="p-3 rounded-3 bg-body-secondary border border-secondary border-opacity-25 shadow-sm d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-2.5 %s" style="%s">
                        <div class="d-flex align-items-center gap-2.5 min-w-0 flex-grow-1">
                            <i class="%s fs-3 flex-shrink-0"></i>
                            <div class="min-w-0 flex-grow-1 overflow-hidden">
                                <div class="text-body fw-bold small text-truncate" title="%s">%s</div>
                                <small class="text-secondary style-tiny font-monospace d-block">%s</small>
                            </div>
                        </div>
                        <a href="%s" target="_blank" class="btn btn-red btn-sm px-3.5 py-1.5 font-monospace style-tiny fw-bold shadow-sm rounded-pill text-center flex-shrink-0">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka / Unduh
                        </a>
                    </div>',
                    $class,
                    $style,
                    $icon,
                    $fileName,
                    $fileName,
                    $subLabel,
                    esc($url)
                );
        }
    }

    /**
     * Format bytes to readable size
     */
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1024 ** $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
