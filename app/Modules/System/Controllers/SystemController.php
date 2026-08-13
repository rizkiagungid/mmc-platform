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

    public function clearAuditLogs()
    {
        if (session()->get('role_slug') !== 'superadmin') {
            return redirect()->to('/admin/audit-logs')->with('error', 'Akses ditolak! Hanya Superadmin yang berhak menghapus audit logs.');
        }

        $this->auditLogModel->emptyTable();
        $this->auditLogModel->recordLog(session()->get('user_id'), 'CLEAR_AUDIT_LOGS', 'Menghapus seluruh riwayat rekam jejak audit logs sistem.');

        return redirect()->to('/admin/audit-logs')->with('success', 'Seluruh riwayat audit logs berhasil dibersihkan.');
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

        // Stats for Chat Inbox & Feed Assets
        $db = \Config\Database::connect();
        $chatMsgCount  = $db->table('chat_messages')->countAllResults();
        $chatConvCount = $db->table('chat_conversations')->countAllResults();
        $feedPostCount = $db->table('posts')->where('deleted_at IS NULL')->countAllResults();
        $notifTotalCount = $db->table('notifications')->countAllResults();
        $notifUnreadCount = $db->table('notifications')->where('is_read', 0)->countAllResults();

        $avatarUsersCount = $db->table('users')
                              ->where('avatar IS NOT NULL')
                              ->where('avatar !=', '')
                              ->where('deleted_at IS NULL')
                              ->countAllResults();

        $chatAttachStats = $this->getDirectoryStats(ROOTPATH . 'public/uploads/chat_attachments');
        $groupIconStats  = $this->getDirectoryStats(ROOTPATH . 'public/uploads/group_icons');
        $feedMediaStats  = $this->getDirectoryStats(ROOTPATH . 'public/uploads/feed_media');
        $avatarStats     = $this->getDirectoryStats(ROOTPATH . 'public/uploads/avatars');

        $chatTotalAssetsSize = $chatAttachStats['size_bytes'] + $groupIconStats['size_bytes'];
        $chatTotalAssetFiles = $chatAttachStats['file_count'] + $groupIconStats['file_count'];

        return view('App\Modules\System\Views\settings', [
            'title'            => 'Pengaturan Sistem & Platform',
            'settings'         => $settingMap,
            'cacheStats'       => $cacheStats,
            'logStats'         => $logStats,
            'totalStats'       => [
                'size_bytes'     => $totalSizeBytes,
                'formatted_size' => $this->formatBytes($totalSizeBytes),
                'file_count'     => $totalFiles,
            ],
            'chatStats'        => [
                'msg_count'      => $chatMsgCount,
                'conv_count'     => $chatConvCount,
                'file_count'     => $chatTotalAssetFiles,
                'formatted_size' => $this->formatBytes($chatTotalAssetsSize),
            ],
            'feedStats'        => [
                'post_count'     => $feedPostCount,
                'file_count'     => $feedMediaStats['file_count'],
                'formatted_size' => $this->formatBytes($feedMediaStats['size_bytes']),
            ],
            'notifStats'       => [
                'total_count'    => $notifTotalCount,
                'unread_count'   => $notifUnreadCount,
            ],
            'avatarStats'      => [
                'user_count'     => $avatarUsersCount,
                'file_count'     => $avatarStats['file_count'],
                'formatted_size' => $this->formatBytes($avatarStats['size_bytes']),
            ],
        ]);
    }

    public function updateSettings()
    {
        $posts = $this->request->getPost();

        // Handle switch checkboxes
        $posts['enable_registration']   = isset($posts['enable_registration']) ? '1' : '0';
        $posts['maintenance_mode']      = isset($posts['maintenance_mode']) ? '1' : '0';
        $posts['lock_member_activities'] = isset($posts['lock_member_activities']) ? '1' : '0';

        // Handle maintenance_pages array
        if (isset($posts['maintenance_pages']) && is_array($posts['maintenance_pages'])) {
            $posts['maintenance_pages'] = json_encode(array_values($posts['maintenance_pages']));
        } else {
            $posts['maintenance_pages'] = '[]';
        }

        // Handle disabled_member_pages array
        if (isset($posts['disabled_member_pages']) && is_array($posts['disabled_member_pages'])) {
            $posts['disabled_member_pages'] = json_encode(array_values($posts['disabled_member_pages']));
        } else {
            $posts['disabled_member_pages'] = '[]';
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

    /**
     * Clear All User Inbox (Private & Group) + Delete Physical Assets from Disk
     */
    public function clearAllInbox()
    {
        $roleSlug = session()->get('role_slug');
        if (!in_array($roleSlug, ['superadmin', 'pembina', 'bph'])) {
            return redirect()->to('/admin/settings')->with('error', 'Akses ditolak! Anda tidak memiliki izin untuk mengosongkan inbox chat.');
        }

        $db = \Config\Database::connect();
        $deletedFiles = 0;

        // 1. Delete physical attachment files from chat_messages
        $messages = $db->table('chat_messages')
                       ->select('attachment_url')
                       ->where('attachment_url IS NOT NULL')
                       ->where('attachment_url !=', '')
                       ->get()->getResultArray();

        foreach ($messages as $msg) {
            $filePath = ROOTPATH . 'public/' . ltrim($msg['attachment_url'], '/');
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
                $deletedFiles++;
            }
        }

        // 2. Delete physical group icons from chat_conversations
        $conversations = $db->table('chat_conversations')
                            ->select('icon')
                            ->where('icon IS NOT NULL')
                            ->where('icon !=', '')
                            ->get()->getResultArray();

        foreach ($conversations as $conv) {
            $filePath = ROOTPATH . 'public/' . ltrim($conv['icon'], '/');
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
                $deletedFiles++;
            }
        }

        // 3. Clean up upload directories if files remain
        $deletedFiles += $this->deleteDirectoryContents(ROOTPATH . 'public/uploads/chat_attachments');
        $deletedFiles += $this->deleteDirectoryContents(ROOTPATH . 'public/uploads/group_icons');

        // 4. Truncate tables
        $db->table('chat_messages')->emptyTable();
        $db->table('chat_participants')->emptyTable();
        $db->table('chat_conversations')->emptyTable();

        $this->auditLogModel->recordLog(
            session()->get('user_id'),
            'SYSTEM_CLEAR_ALL_INBOX',
            "Pengosongan total inbox chat pribadi & grup. {$deletedFiles} berkas lampiran fisik telah dihapus dari server disk."
        );

        return redirect()->to('/admin/settings')->with('success', "Seluruh inbox obrolan pribadi & grup berhasil dikosongkan. {$deletedFiles} berkas lampiran fisik telah dihapus dari server disk!");
    }

    /**
     * Clear All User Social Feed + Delete Physical Assets from Disk
     */
    public function clearAllFeed()
    {
        $roleSlug = session()->get('role_slug');
        if (!in_array($roleSlug, ['superadmin', 'pembina', 'bph'])) {
            return redirect()->to('/admin/settings')->with('error', 'Akses ditolak! Anda tidak memiliki izin untuk mengosongkan beranda feed.');
        }

        $db = \Config\Database::connect();
        $deletedFiles = 0;

        // 1. Delete physical media files from posts
        $posts = $db->table('posts')
                    ->select('media_url')
                    ->where('media_url IS NOT NULL')
                    ->where('media_url !=', '')
                    ->get()->getResultArray();

        foreach ($posts as $post) {
            $filePath = ROOTPATH . 'public/' . ltrim($post['media_url'], '/');
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
                $deletedFiles++;
            }
        }

        // 2. Clean up upload directory if files remain
        $deletedFiles += $this->deleteDirectoryContents(ROOTPATH . 'public/uploads/feed_media');

        // 3. Truncate tables
        $db->table('post_likes')->emptyTable();
        $db->table('post_comments')->emptyTable();
        $db->table('posts')->emptyTable();

        $this->auditLogModel->recordLog(
            session()->get('user_id'),
            'SYSTEM_CLEAR_ALL_FEED',
            "Pengosongan total beranda feed sosial. {$deletedFiles} berkas foto/video media fisik telah dihapus dari server disk."
        );

        return redirect()->to('/admin/settings')->with('success', "Seluruh postingan beranda feed sosial berhasil dikosongkan. {$deletedFiles} berkas foto/video media fisik telah dihapus dari server disk!");
    }

    /**
     * Clear All User Notifications
     */
    public function clearAllNotifications()
    {
        $roleSlug = session()->get('role_slug');
        if (!in_array($roleSlug, ['superadmin', 'pembina', 'bph'])) {
            return redirect()->to('/admin/settings')->with('error', 'Akses ditolak! Anda tidak memiliki izin untuk mengosongkan notifikasi.');
        }

        $db = \Config\Database::connect();
        $notifCount = $db->table('notifications')->countAllResults();

        $db->table('notifications')->emptyTable();

        $this->auditLogModel->recordLog(
            session()->get('user_id'),
            'SYSTEM_CLEAR_ALL_NOTIFICATIONS',
            "Pengosongan total notifikasi pengguna ({$notifCount} notifikasi dihapus)."
        );

        return redirect()->to('/admin/settings')->with('success', "Seluruh notifikasi pengguna berhasil dikosongkan. {$notifCount} notifikasi telah dihapus dari database!");
    }

    /**
     * Clear All User Avatars + Delete Physical Files from Disk
     */
    public function clearAllAvatars()
    {
        $roleSlug = session()->get('role_slug');
        if (!in_array($roleSlug, ['superadmin', 'pembina', 'bph'])) {
            return redirect()->to('/admin/settings')->with('error', 'Akses ditolak! Anda tidak memiliki izin untuk mengosongkan foto profil.');
        }

        $db = \Config\Database::connect();
        $deletedFiles = 0;

        // 1. Delete physical avatar files from users table
        $users = $db->table('users')
                    ->select('id, avatar')
                    ->where('avatar IS NOT NULL')
                    ->where('avatar !=', '')
                    ->get()->getResultArray();

        foreach ($users as $user) {
            if (!empty($user['avatar'])) {
                $filePath = ROOTPATH . 'public/' . ltrim($user['avatar'], '/');
                if (file_exists($filePath) && is_file($filePath)) {
                    @unlink($filePath);
                    $deletedFiles++;
                }
            }
        }

        // 2. Clean up upload directory if files remain
        $deletedFiles += $this->deleteDirectoryContents(ROOTPATH . 'public/uploads/avatars');

        // 3. Reset avatar column in users table
        $db->table('users')->update(['avatar' => null]);

        // 4. Update session if active user had an avatar
        session()->set('avatar', null);

        $this->auditLogModel->recordLog(
            session()->get('user_id'),
            'SYSTEM_CLEAR_ALL_AVATARS',
            "Pengosongan total foto profil pengguna. {$deletedFiles} berkas foto profil fisik telah dihapus dari server disk."
        );

        return redirect()->to('/admin/settings')->with('success', "Seluruh foto profil akun pengguna berhasil dihapus. {$deletedFiles} berkas fisik foto profil telah dibersihkan dari server disk!");
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
