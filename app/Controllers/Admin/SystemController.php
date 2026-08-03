<?php

namespace App\Controllers\Admin;

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
        $logs = $this->auditLogModel->select('audit_logs.*, users.full_name, users.username, roles.name as role_name')
                                    ->join('users', 'users.id = audit_logs.user_id', 'left')
                                    ->join('roles', 'roles.id = users.role_id', 'left')
                                    ->orderBy('audit_logs.created_at', 'DESC')
                                    ->findAll(200);

        return view('admin/system/audit_logs', [
            'title' => 'Audit Log & Activity Records - Admin CMS',
            'logs'  => $logs,
        ]);
    }

    public function settings()
    {
        $allSettings = $this->settingModel->findAll();
        $settingsMap = [];
        foreach ($allSettings as $s) {
            $settingsMap[$s['setting_key']] = $s['setting_value'];
        }

        return view('admin/system/settings', [
            'title'    => 'Pengaturan Aplikasi & Klub - Admin CMS',
            'settings' => $settingsMap,
        ]);
    }

    public function updateSettings()
    {
        $posts = $this->request->getPost();
        foreach ($posts as $key => $val) {
            if ($key !== 'csrf_test_name') {
                $this->settingModel->setSetting($key, (string)$val);
            }
        }

        $this->auditLogModel->recordLog(session()->get('user_id'), 'SETTING_UPDATE', 'Mengubah pengaturan konfigurasi sistem');

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
