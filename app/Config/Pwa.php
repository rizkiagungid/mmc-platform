<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pwa extends BaseConfig
{
    public string $appName           = 'Multimedia Club SMAN 1 Tamansari';
    public string $shortName         = 'MMC Platform';
    public string $description       = 'Official Member Platform & Learning Center Multimedia Club SMAN 1 Tamansari';
    public string $themeColor        = '#0d1117';
    public string $backgroundColor   = '#0d1117';
    public string $accentColor       = '#dc2626';
    public string $cacheVersion      = '1.0.0';
    public bool $enableSw            = true;
    public bool $enableOffline       = true;
    public bool $enableInstallPrompt = true;
    public int $postponeDays         = 7;

    /**
     * Generate dynamic cache version string automatically based on SW file modification timestamp
     */
    public function getDynamicVersion(): string
    {
        $swPath = ROOTPATH . 'public/sw.js';
        $mtime  = file_exists($swPath) ? filemtime($swPath) : time();
        return $this->cacheVersion . '.' . $mtime;
    }
}
