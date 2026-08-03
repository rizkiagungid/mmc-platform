<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Member attendance views & APIs
    $routes->get('attendance/scan', '\App\Modules\Attendance\Controllers\AttendanceController::scanMeetingQr');
    $routes->post('attendance/process-scan', '\App\Modules\Attendance\Controllers\AttendanceController::processScanApi');
    $routes->post('attendance/process-pin', '\App\Modules\Attendance\Controllers\AttendanceController::processPinApi');
    $routes->get('attendance/history', '\App\Modules\Attendance\Controllers\AttendanceController::history');
});

$routes->group('admin', ['filter' => ['auth', 'role:superadmin,pembina,bph']], static function ($routes) {
    // Admin attendance views & manual check-in
    $routes->get('attendance', '\App\Modules\Attendance\Controllers\AttendanceController::index');
    $routes->get('attendance/scan-member', '\App\Modules\Attendance\Controllers\AttendanceController::scanMemberQr');
    $routes->post('attendance/manual', '\App\Modules\Attendance\Controllers\AttendanceController::manualStore');
});
