<?php

namespace App\Modules\System\Controllers;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Models\SettingModel;

class SystemController extends BaseController
{
    protected $auditLogModel;
    protected $settingModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
        $this->settingModel  = new SettingModel();
    }

    public function auditLogs()
    {
        $logs = $this->auditLogModel->select('audit_logs.*, users.full_name, users.username, users.nis_nip')
                                    ->join('users', 'users.id = audit_logs.user_id', 'left')
                                    ->orderBy('audit_logs.created_at', 'DESC')
                                    ->findAll(200);

        return view('App\Modules\System\Views\logs', [
            'title' => 'Audit Logs System - Multimedia Club',
            'logs'  => $logs,
        ]);
    }

    public function settings()
    {
        $settings = $this->settingModel->findAll();
        $settingMap = [];
        foreach ($settings as $s) {
            $settingMap[$s['setting_key']] = $s['setting_value'];
        }

        $cacheStats = $this->getDirectoryStats(WRITEPATH . 'cache');
        $logStats   = $this->getDirectoryStats(WRITEPATH . 'logs');

        $totalSizeBytes = $cacheStats['size_bytes'] + $logStats['size_bytes'];
        $totalFiles     = $cacheStats['file_count'] + $logStats['file_count'];

        return view('App\Modules\System\Views\settings', [
            'title'      => 'Pengaturan Sistem & Platform',
            'settings'   => $settingMap,
            'cacheStats' => $cacheStats,
            'logStats'   => $logStats,
            'totalStats' => [
                'size_bytes'     => $totalSizeBytes,
                'formatted_size' => $this->formatBytes($totalSizeBytes),
                'file_count'     => $totalFiles,
            ],
        ]);
    }

    public function updateSettings()
    {
        $posts = $this->request->getPost();

        // Handle switch checkboxes
        $posts['enable_registration'] = isset($posts['enable_registration']) ? '1' : '0';
        $posts['maintenance_mode']    = isset($posts['maintenance_mode']) ? '1' : '0';

        // Handle maintenance_pages array
        if (isset($posts['maintenance_pages']) && is_array($posts['maintenance_pages'])) {
            $posts['maintenance_pages'] = json_encode(array_values($posts['maintenance_pages']));
        } else {
            $posts['maintenance_pages'] = '[]';
        }

        // Handle Logo file upload
        $logoFile = $this->request->getFile('site_logo_file');
        if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
            $newName = $logoFile->getRandomName();
            $targetDir = ROOTPATH . 'public/uploads/settings';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $logoFile->move($targetDir, $newName);
            $posts['site_logo'] = 'uploads/settings/' . $newName;
        }

        // Handle Favicon file upload
        $faviconFile = $this->request->getFile('site_favicon_file');
        if ($faviconFile && $faviconFile->isValid() && !$faviconFile->hasMoved()) {
            $newName = $faviconFile->getRandomName();
            $targetDir = ROOTPATH . 'public/uploads/settings';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $faviconFile->move($targetDir, $newName);
            $posts['site_favicon'] = 'uploads/settings/' . $newName;
        }

        foreach ($posts as $key => $val) {
            if ($key === 'csrf_test_name' || strpos($key, '_file') !== false) continue;
            $this->settingModel->setSetting($key, is_array($val) ? json_encode($val) : trim((string)$val));
        }

        $this->auditLogModel->recordLog(session()->get('user_id'), 'SYSTEM_SETTINGS_UPDATE', 'Memperbarui pengaturan umum, SEO, maintenance mode, & status pemeliharaan per-halaman.');

        return redirect()->to('/admin/settings')->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }

    public function clearCache()
    {
        try {
            cache()->clean();
        } catch (\Throwable $e) {
        }

        $deletedCount = $this->deleteDirectoryContents(WRITEPATH . 'cache');

        $this->auditLogModel->recordLog(session()->get('user_id'), 'SYSTEM_CACHE_CLEARED', "Memposisikan dan membersihkan {$deletedCount} file cache sistem.");

        return redirect()->to('/admin/settings')->with('success', "Berhasil membersihkan {$deletedCount} file cache sistem.");
    }

    public function clearLogs()
    {
        $deletedCount = $this->deleteDirectoryContents(WRITEPATH . 'logs');

        $this->auditLogModel->recordLog(session()->get('user_id'), 'SYSTEM_LOGS_CLEARED', "Menghapus {$deletedCount} file log sistem.");

        return redirect()->to('/admin/settings')->with('success', "Berhasil menghapus {$deletedCount} file log sistem.");
    }

    public function clearAllStorage()
    {
        try {
            cache()->clean();
        } catch (\Throwable $e) {
        }

        $cacheDeleted = $this->deleteDirectoryContents(WRITEPATH . 'cache');
        $logsDeleted  = $this->deleteDirectoryContents(WRITEPATH . 'logs');

        $totalDeleted = $cacheDeleted + $logsDeleted;

        $this->auditLogModel->recordLog(session()->get('user_id'), 'SYSTEM_ALL_STORAGE_CLEARED', "Pembersihan total storage temp ({$totalDeleted} file cache & log dihapus).");

        return redirect()->to('/admin/settings')->with('success', "Pembersihan total berhasil! {$totalDeleted} file cache dan log telah dihapus.");
    }

    private function getDirectoryStats(string $dirPath): array
    {
        $sizeBytes = 0;
        $fileCount = 0;

        if (is_dir($dirPath)) {
            $files = array_diff(scandir($dirPath), ['.', '..', 'index.html', '.gitkeep']);
            foreach ($files as $file) {
                $filePath = $dirPath . DIRECTORY_SEPARATOR . $file;
                if (is_file($filePath)) {
                    $sizeBytes += filesize($filePath);
                    $fileCount++;
                }
            }
        }

        return [
            'size_bytes'     => $sizeBytes,
            'formatted_size' => $this->formatBytes($sizeBytes),
            'file_count'     => $fileCount,
        ];
    }

    private function deleteDirectoryContents(string $dirPath): int
    {
        $deleted = 0;
        if (is_dir($dirPath)) {
            $files = array_diff(scandir($dirPath), ['.', '..', 'index.html', '.gitkeep']);
            foreach ($files as $file) {
                $filePath = $dirPath . DIRECTORY_SEPARATOR . $file;
                if (is_file($filePath)) {
                    @unlink($filePath);
                    $deleted++;
                }
            }
        }
        return $deleted;
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
