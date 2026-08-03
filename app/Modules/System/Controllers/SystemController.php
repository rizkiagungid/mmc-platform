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

        foreach ($posts as $key => $val) {
            if ($key === 'csrf_test_name') continue;
            $this->settingModel->setSetting($key, is_array($val) ? json_encode($val) : trim((string)$val));
        }

        $this->auditLogModel->recordLog(session()->get('user_id'), 'SYSTEM_SETTINGS_UPDATE', 'Memperbarui pengaturan umum platform.');

        return redirect()->to('/admin/settings')->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
