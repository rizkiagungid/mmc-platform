<?php

use App\Libraries\MediaManager;

if (!function_exists('media_url')) {
    /**
     * Resolve full dynamic URL for any media path across environments.
     */
    function media_url(?string $path, ?string $fallback = null): string
    {
        return MediaManager::getUrl($path, $fallback);
    }
}

if (!function_exists('media_type')) {
    /**
     * Get media category (image, video, audio, document, archive, other).
     */
    function media_type(?string $path): string
    {
        return MediaManager::getType($path);
    }
}

if (!function_exists('media_info')) {
    /**
     * Get full metadata and UI helper info for media file.
     */
    function media_info(?string $path): array
    {
        return MediaManager::getInfo($path);
    }
}

if (!function_exists('render_media')) {
    /**
     * Render dynamic HTML element (img, video, audio, or document card) based on file type.
     */
    function render_media(?string $path, array $options = []): string
    {
        return MediaManager::render($path, $options);
    }
}

if (!function_exists('clean_media_path')) {
    /**
     * Normalize path to clean relative path (e.g. "uploads/tasks/foo.png").
     */
    function clean_media_path(?string $path): string
    {
        return MediaManager::cleanPath($path);
    }
}

if (!function_exists('avatar_url')) {
    /**
     * Resolve Avatar URL with automatic fallback to initial avatar if missing.
     */
    function avatar_url(?string $avatar, string $name = 'User'): string
    {
        return MediaManager::getAvatarUrl($avatar, $name);
    }
}

