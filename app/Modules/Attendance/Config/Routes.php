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
    $routes->get('attendance/export', '\App\Modules\Attendance\Controllers\AttendanceController::export');
    $routes->get('attendance/scan-member', '\App\Modules\Attendance\Controllers\AttendanceController::scanMemberQr');
    $routes->post('attendance/manual', '\App\Modules\Attendance\Controllers\AttendanceController::manualStore');
    $routes->post('attendance/update/(:num)', '\App\Modules\Attendance\Controllers\AttendanceController::update/$1');
    $routes->get('attendance/delete/(:num)', '\App\Modules\Attendance\Controllers\AttendanceController::delete/$1');
});
