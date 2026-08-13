<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class MaintenanceFilter implements FilterInterface
{
    public function before(RequestInterface $request, $params = null)
    {
        helper('setting');
        $maintenanceMode     = get_setting('maintenance_mode', '0');
        $maintenancePagesRaw = get_setting('maintenance_pages', '[]');
        $maintenancePages    = json_decode($maintenancePagesRaw, true) ?: [];

        // Always allow admin routes, login routes, auth routes, assets, uploads, and PWA files
        if (
            url_is('login*') ||
            url_is('auth*') ||
            url_is('admin*') ||
            url_is('assets*') ||
            url_is('uploads*') ||
            url_is('manifest.json') ||
            url_is('sw.js') ||
            url_is('offline.html')
        ) {
            return;
        }

        $roleSlug = session()->get('role_slug');

        // Check Member Activity Lock setting (Freeze member actions)
        $lockMemberActivities = get_setting('lock_member_activities', '0');
        if ($lockMemberActivities === '1' && !in_array($roleSlug, ['superadmin', 'pembina', 'bph'])) {
            $httpMethod = strtolower((string)$request->getMethod());
            if (in_array($httpMethod, ['post', 'put', 'delete', 'patch'], true)) {
                if ($request->isAJAX() || str_contains($request->getHeaderLine('Accept'), 'application/json')) {
                    return response()->setStatusCode(403)->setJSON([
                        'status'  => 'error',
                        'message' => 'Aktivitas anggota sedang dinonaktifkan oleh Administrator / BPH.'
                    ]);
                }
                return redirect()->back()->with('error', 'Aktivitas anggota sedang dibekukan / dinonaktifkan oleh Administrator / BPH.');
            }
        }
        // Check Page-Specific Lock for Members (Nonaktifkan Halaman Tertentu Anggota)
        $disabledMemberPagesRaw = get_setting('disabled_member_pages', '[]');
        $disabledMemberPages    = json_decode($disabledMemberPagesRaw, true) ?: [];

        if (!empty($disabledMemberPages) && !in_array($roleSlug, ['superadmin', 'pembina', 'bph'])) {
            $isMemberPageLocked = false;

            if (in_array('attendance_scan', $disabledMemberPages) && url_is('attendance/scan*')) {
                $isMemberPageLocked = true;
            } elseif (in_array('attendance_history', $disabledMemberPages) && url_is('attendance/history*')) {
                $isMemberPageLocked = true;
            } elseif (in_array('tasks', $disabledMemberPages) && (url_is('member/tasks*') || url_is('tasks*'))) {
                $isMemberPageLocked = true;
            } elseif (in_array('learning', $disabledMemberPages) && (url_is('member/learning*') || url_is('learning*'))) {
                $isMemberPageLocked = true;
            } elseif (in_array('feed', $disabledMemberPages) && url_is('feed*')) {
                $isMemberPageLocked = true;
            } elseif (in_array('inbox', $disabledMemberPages) && url_is('inbox*')) {
                $isMemberPageLocked = true;
            } elseif (in_array('information', $disabledMemberPages) && url_is('informasi*')) {
                $isMemberPageLocked = true;
            } elseif (in_array('messages', $disabledMemberPages) && url_is('admin/cms/messages*')) {
                $isMemberPageLocked = true;
            } elseif (in_array('profile', $disabledMemberPages) && url_is('profile*')) {
                $isMemberPageLocked = true;
            }

            if ($isMemberPageLocked) {
                if ($request->isAJAX() || str_contains($request->getHeaderLine('Accept'), 'application/json')) {
                    return response()->setStatusCode(403)->setJSON([
                        'status'  => 'error',
                        'message' => 'Halaman ini sedang dinonaktifkan sementara oleh Pengurus / Admin.'
                    ]);
                }
                return redirect()->to('/dashboard')->with('error', 'Halaman yang Anda akses sedang dinonaktifkan sementara oleh Pengurus / Admin.');
            }
        }

        // Allow logged-in admin roles to bypass maintenance mode
        if (in_array($roleSlug, ['superadmin', 'pembina', 'bph'])) {
            return;
        }

        $isPageInMaintenance = false;

        if ($maintenanceMode === '1') {
            $isPageInMaintenance = true;
        } elseif (!empty($maintenancePages)) {
            if (in_array('home', $maintenancePages) && (url_is('/') || url_is(''))) {
                $isPageInMaintenance = true;
            } elseif (in_array('about', $maintenancePages) && url_is('about*')) {
                $isPageInMaintenance = true;
            } elseif (in_array('learning-path', $maintenancePages) && url_is('learning-path*')) {
                $isPageInMaintenance = true;
            } elseif (in_array('portfolio', $maintenancePages) && url_is('portfolio*')) {
                $isPageInMaintenance = true;
            } elseif (in_array('gallery', $maintenancePages) && url_is('gallery*')) {
                $isPageInMaintenance = true;
            } elseif (in_array('faq', $maintenancePages) && url_is('faq*')) {
                $isPageInMaintenance = true;
            } elseif (in_array('achievements', $maintenancePages) && (url_is('achievements*') || url_is('prestasi*'))) {
                $isPageInMaintenance = true;
            }
        }

        if ($isPageInMaintenance) {
            // Display Maintenance Mode Page
            $msg = get_setting('maintenance_message', 'Situs web saat ini sedang dalam pemeliharaan sistem. Silakan kembali beberapa saat lagi.');
            $siteTitle = get_setting('site_title', 'Multimedia Club SMAN 1 Tamansari');
            $logo = get_setting('site_logo', 'assets/logo-mm-2023.png');

            $html = view('errors/maintenance', [
                'message'   => $msg,
                'siteTitle' => $siteTitle,
                'logo'      => $logo,
            ]);

            return response()->setStatusCode(503)->setBody($html);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $params = null)
    {
        // No action needed after
    }
}
