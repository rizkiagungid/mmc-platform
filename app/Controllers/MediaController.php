<?php

namespace App\Controllers;

use App\Libraries\MediaManager;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Media Controller
 * 
 * Handles dev proxy streaming from production fallback host,
 * and dynamic SVG placeholder generation.
 */
class MediaController extends Controller
{
    /**
     * Proxy and stream media asset from production if not available locally.
     * Optionally caches the asset locally to speed up subsequent local requests.
     */
    public function proxy(): ResponseInterface
    {
        $rawPath = $this->request->getGet('path');
        if (empty($rawPath)) {
            return $this->placeholder();
        }

        $cleanPath = MediaManager::cleanPath($rawPath);
        $localFile = FCPATH . ltrim($cleanPath, '/');

        // If file already exists locally, serve directly
        if (file_exists($localFile) && is_file($localFile)) {
            return $this->serveLocalFile($localFile);
        }

        // Only allow proxy in development or if explicitly enabled
        $isDev = (env('CI_ENVIRONMENT', 'development') === 'development');
        $prodUrl = rtrim(env('media.prodFallbackURL', ''), '/');

        if (!$isDev || empty($prodUrl)) {
            return $this->placeholder($cleanPath);
        }

        $remoteUrl = $prodUrl . '/' . ltrim($cleanPath, '/');

        // Fetch from remote production server
        try {
            $client = \Config\Services::curlrequest([
                'timeout'         => 10,
                'connect_timeout' => 5,
                'http_errors'     => false,
                'verify'          => false,
            ]);

            $response = $client->get($remoteUrl);

            if ($response->getStatusCode() === 200) {
                $body = $response->getBody();
                $contentType = $response->getHeaderLine('Content-Type') ?: $this->detectMimeType($cleanPath);

                // Auto-cache locally in public/uploads/ for faster subsequent loads
                $dir = dirname($localFile);
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
                @file_put_contents($localFile, $body);

                return $this->response
                            ->setHeader('Content-Type', $contentType)
                            ->setHeader('Cache-Control', 'public, max-age=86400')
                            ->setBody($body);
            }
        } catch (\Throwable $e) {
            log_message('debug', 'Media proxy error: ' . $e->getMessage());
        }

        // Fallback to placeholder SVG
        return $this->placeholder($cleanPath);
    }

    /**
     * Serve a local file with proper headers
     */
    protected function serveLocalFile(string $filePath): ResponseInterface
    {
        $mime = mime_content_type($filePath) ?: 'application/octet-stream';
        $content = file_get_contents($filePath);

        return $this->response
                    ->setHeader('Content-Type', $mime)
                    ->setHeader('Content-Length', (string)filesize($filePath))
                    ->setHeader('Cache-Control', 'public, max-age=86400')
                    ->setBody($content);
    }

    /**
     * Generate dynamic SVG placeholder with clean iconography and text
     */
    public function placeholder(?string $path = null): ResponseInterface
    {
        $path = $path ?? $this->request->getGet('path') ?? 'Media';
        $type = $this->request->getGet('type') ?? MediaManager::getType($path);
        $fileName = basename($path);

        $color = '#64748b';
        $bgColor = '#1e293b';
        $label = 'FILE NOT FOUND';
        $iconSymbol = '📁';

        switch ($type) {
            case 'image':
                $label = 'IMAGE NOT FOUND';
                $iconSymbol = '🖼️';
                $color = '#38bdf8';
                break;
            case 'video':
                $label = 'VIDEO PREVIEW';
                $iconSymbol = '🎬';
                $color = '#818cf8';
                break;
            case 'audio':
                $label = 'AUDIO FILE';
                $iconSymbol = '🎵';
                $color = '#c084fc';
                break;
            case 'document':
                $label = 'DOCUMENT';
                $iconSymbol = '📄';
                $color = '#f87171';
                break;
            case 'archive':
                $label = 'ARCHIVE';
                $iconSymbol = '📦';
                $color = '#fbbf24';
                break;
        }

        $safeText = htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8');
        if (strlen($safeText) > 24) {
            $safeText = substr($safeText, 0, 22) . '...';
        }

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="400" height="240" viewBox="0 0 400 240" fill="none">
    <rect width="400" height="240" rx="12" fill="{$bgColor}"/>
    <rect x="1" y="1" width="398" height="238" rx="11" stroke="rgba(255,255,255,0.08)" stroke-width="2"/>
    <text x="200" y="90" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="42" text-anchor="middle" dominant-baseline="middle">{$iconSymbol}</text>
    <text x="200" y="145" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="12" font-weight="700" letter-spacing="1.5" fill="{$color}" text-anchor="middle">{$label}</text>
    <text x="200" y="175" font-family="monospace" font-size="11" fill="#94a3b8" text-anchor="middle">{$safeText}</text>
</svg>
SVG;

        return $this->response
                    ->setHeader('Content-Type', 'image/svg+xml')
                    ->setHeader('Cache-Control', 'public, max-age=3600')
                    ->setBody($svg);
    }

    /**
     * Generate dynamic circular SVG initial avatar based on user name
     */
    public function avatar(): ResponseInterface
    {
        $name = trim($this->request->getGet('name') ?: 'User');
        $initial = strtoupper(mb_substr($name, 0, 1, 'UTF-8')) ?: 'U';

        // Vibrant distinct background colors
        $colors = ['#ef4444', '#f97316', '#f59e0b', '#10b981', '#06b6d4', '#3b82f6', '#6366f1', '#8b5cf6', '#ec4899', '#14b8a6'];
        $colorIndex = abs(crc32($name)) % count($colors);
        $bgColor = $colors[$colorIndex];

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128">
    <rect width="128" height="128" rx="64" fill="{$bgColor}"/>
    <text x="64" y="70" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="54" font-weight="700" fill="#ffffff" text-anchor="middle" dominant-baseline="middle">{$initial}</text>
</svg>
SVG;

        return $this->response
                    ->setHeader('Content-Type', 'image/svg+xml')
                    ->setHeader('Cache-Control', 'public, max-age=86400')
                    ->setBody($svg);
    }

    /**
     * Basic MIME detection fallback
     */
    protected function detectMimeType(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $map = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
            'svg'  => 'image/svg+xml',
            'mp4'  => 'video/mp4',
            'webm' => 'video/webm',
            'mp3'  => 'audio/mpeg',
            'wav'  => 'audio/wav',
            'pdf'  => 'application/pdf',
            'zip'  => 'application/zip',
            'rar'  => 'application/x-rar-compressed',
        ];

        return $map[$ext] ?? 'application/octet-stream';
    }
}
