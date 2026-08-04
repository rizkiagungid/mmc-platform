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

        // Allow logged-in admin roles to bypass maintenance mode
        $roleSlug = session()->get('role_slug');
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
