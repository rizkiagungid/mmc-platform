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

        return view('App\Modules\System\Views\settings', [
            'title'    => 'Pengaturan Sistem & Platform',
            'settings' => $settingMap,
        ]);
    }

    public function updateSettings()
    {
        $posts = $this->request->getPost();

        // Handle switch checkboxes
        if (!isset($posts['enable_registration'])) {
            $posts['enable_registration'] = '0';
        }
        if (!isset($posts['maintenance_mode'])) {
            $posts['maintenance_mode'] = '0';
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

        $this->auditLogModel->recordLog(session()->get('user_id'), 'SYSTEM_SETTINGS_UPDATE', 'Memperbarui pengaturan umum, SEO, & status pemeliharaan platform.');

        return redirect()->to('/admin/settings')->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
