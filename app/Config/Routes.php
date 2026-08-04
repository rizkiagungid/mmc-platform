<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// 1. Public Website Routes
$routes->get('/', 'PublicController::index');
$routes->get('about', 'PublicController::about');
$routes->get('learning-path', 'PublicController::learningPath');
$routes->get('portfolio', 'PublicController::portfolio');
$routes->get('gallery', 'PublicController::gallery');
$routes->get('faq', 'PublicController::faq');
$routes->get('achievements', 'PublicController::achievements');
$routes->get('prestasi', 'PublicController::achievements');

// 2. Authentication Routes (Modular Auth)
$routes->get('login', '\App\Modules\Auth\Controllers\AuthController::login');
$routes->post('login', '\App\Modules\Auth\Controllers\AuthController::attemptLogin');
$routes->get('register', '\App\Modules\Auth\Controllers\AuthController::register');
$routes->post('register', '\App\Modules\Auth\Controllers\AuthController::attemptRegister');
$routes->get('logout', '\App\Modules\Auth\Controllers\AuthController::logout');

// 3. Member & Shared Authenticated Routes
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Member Attendance Scanners & History (Modular Attendance)
    $routes->get('attendance/scan', '\App\Modules\Attendance\Controllers\AttendanceController::scanMeetingQr');
    $routes->post('attendance/process-scan', '\App\Modules\Attendance\Controllers\AttendanceController::processScanApi');
    $routes->post('attendance/process-pin', '\App\Modules\Attendance\Controllers\AttendanceController::processPinApi');
    $routes->get('attendance/history', '\App\Modules\Attendance\Controllers\AttendanceController::history');

    // Member Tasks & Submissions (Modular Task)
    $routes->get('member/tasks', '\App\Modules\Task\Controllers\TaskController::myTasks');
    $routes->get('member/tasks/submit/(:num)', '\App\Modules\Task\Controllers\TaskController::submitForm/$1');
    $routes->post('member/tasks/submit/(:num)', '\App\Modules\Task\Controllers\TaskController::submitStore/$1');
    $routes->post('member/tasks/comment/(:num)', '\App\Modules\Task\Controllers\TaskController::postComment/$1');
    $routes->get('profile', '\App\Modules\User\Controllers\UserController::profile');
    $routes->post('profile', '\App\Modules\User\Controllers\UserController::updateProfile');
});

// 4. Admin CMS Routes (Super Admin, Pembina, BPH)
$routes->group('admin', ['filter' => ['auth', 'role:superadmin,pembina,bph']], static function ($routes) {

    // User & Member Management (Modular User)
    $routes->get('users', '\App\Modules\User\Controllers\UserController::index');
    $routes->get('users/create', '\App\Modules\User\Controllers\UserController::create');
    $routes->post('users/store', '\App\Modules\User\Controllers\UserController::store');
    $routes->get('users/edit/(:num)', '\App\Modules\User\Controllers\UserController::edit/$1');
    $routes->post('users/update/(:num)', '\App\Modules\User\Controllers\UserController::update/$1');
    $routes->get('users/delete/(:num)', '\App\Modules\User\Controllers\UserController::delete/$1');
    $routes->get('users/activate/(:num)', '\App\Modules\User\Controllers\UserController::activate/$1');
    $routes->get('users/regenerate-qr/(:num)', '\App\Modules\User\Controllers\UserController::regenerateQr/$1');
    $routes->get('users/qr/(:segment)', '\App\Modules\User\Controllers\UserController::showQr/$1');

    // Meeting Management (Modular Meeting)
    $routes->get('meetings', '\App\Modules\Meeting\Controllers\MeetingController::index');
    $routes->get('meetings/create', '\App\Modules\Meeting\Controllers\MeetingController::create');
    $routes->post('meetings/store', '\App\Modules\Meeting\Controllers\MeetingController::store');
    $routes->get('meetings/edit/(:num)', '\App\Modules\Meeting\Controllers\MeetingController::edit/$1');
    $routes->post('meetings/update/(:num)', '\App\Modules\Meeting\Controllers\MeetingController::update/$1');
    $routes->get('meetings/delete/(:num)', '\App\Modules\Meeting\Controllers\MeetingController::delete/$1');
    $routes->get('meetings/activate/(:num)', '\App\Modules\Meeting\Controllers\MeetingController::activate/$1');
    $routes->get('meetings/qr/(:num)', '\App\Modules\Meeting\Controllers\MeetingController::qrPoster/$1');

    // Attendance Management & Operator Member QR Scanner (Modular Attendance)
    $routes->get('attendance', '\App\Modules\Attendance\Controllers\AttendanceController::index');
    $routes->get('attendance/scan-member', '\App\Modules\Attendance\Controllers\AttendanceController::scanMemberQr');
    $routes->post('attendance/manual', '\App\Modules\Attendance\Controllers\AttendanceController::manualStore');
    $routes->post('attendance/update/(:num)', '\App\Modules\Attendance\Controllers\AttendanceController::update/$1');
    $routes->get('attendance/delete/(:num)', '\App\Modules\Attendance\Controllers\AttendanceController::delete/$1');

    // Task Management & Submissions Evaluation (Modular Task)
    $routes->get('tasks', '\App\Modules\Task\Controllers\TaskController::index');
    $routes->get('tasks/create', '\App\Modules\Task\Controllers\TaskController::create');
    $routes->post('tasks/store', '\App\Modules\Task\Controllers\TaskController::store');
    $routes->get('tasks/detail/(:num)', '\App\Modules\Task\Controllers\TaskController::detail/$1');
    $routes->get('tasks/edit/(:num)', '\App\Modules\Task\Controllers\TaskController::edit/$1');
    $routes->post('tasks/update/(:num)', '\App\Modules\Task\Controllers\TaskController::update/$1');
    $routes->get('tasks/delete/(:num)', '\App\Modules\Task\Controllers\TaskController::delete/$1');
    $routes->post('tasks/evaluate/(:num)', '\App\Modules\Task\Controllers\TaskController::evaluate/$1');
    $routes->post('tasks/update-status/(:num)', '\App\Modules\Task\Controllers\TaskController::quickUpdateStatus/$1');
    $routes->post('tasks/update-priority/(:num)', '\App\Modules\Task\Controllers\TaskController::quickUpdatePriority/$1');
    $routes->post('tasks/update-assignee-status/(:num)', '\App\Modules\Task\Controllers\TaskController::quickUpdateAssigneeStatus/$1');
    $routes->post('tasks/comment/(:num)', '\App\Modules\Task\Controllers\TaskController::postComment/$1');

    // System Settings & Audit Logs (Modular System - Super Admin & Pembina only)
    $routes->group('', ['filter' => 'role:superadmin,pembina'], static function ($routes) {
        $routes->get('audit-logs', '\App\Modules\System\Controllers\SystemController::auditLogs');
        $routes->get('settings', '\App\Modules\System\Controllers\SystemController::settings');
        $routes->post('settings', '\App\Modules\System\Controllers\SystemController::updateSettings');
        $routes->post('system/clear-cache', '\App\Modules\System\Controllers\SystemController::clearCache');
        $routes->post('system/clear-logs', '\App\Modules\System\Controllers\SystemController::clearLogs');
        $routes->post('system/clear-all-storage', '\App\Modules\System\Controllers\SystemController::clearAllStorage');
    });
});

// Load Modular CMS Routes
if (file_exists(APPPATH . 'Modules/Cms/Config/Routes.php')) {
    require APPPATH . 'Modules/Cms/Config/Routes.php';
}

// Load Modular Learning Routes
if (file_exists(APPPATH . 'Modules/Learning/Config/Routes.php')) {
    require APPPATH . 'Modules/Learning/Config/Routes.php';
}

