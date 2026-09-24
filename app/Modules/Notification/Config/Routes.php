<?php

namespace App\Modules\Notification\Config;

$routes = service('routes');

$routes->group('notifications', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', '\App\Modules\Notification\Controllers\NotificationController::index');
    $routes->get('unread-count', '\App\Modules\Notification\Controllers\NotificationController::unreadCount');
    $routes->get('dropdown', '\App\Modules\Notification\Controllers\NotificationController::dropdown');
    $routes->get('mark-read/(:num)', '\App\Modules\Notification\Controllers\NotificationController::markRead/$1');
    $routes->post('mark-read/(:num)', '\App\Modules\Notification\Controllers\NotificationController::markRead/$1');
    $routes->get('mark-all-read', '\App\Modules\Notification\Controllers\NotificationController::markAllRead');
    $routes->post('mark-all-read', '\App\Modules\Notification\Controllers\NotificationController::markAllRead');
    $routes->get('delete/(:num)', '\App\Modules\Notification\Controllers\NotificationController::delete/$1');
    $routes->post('delete/(:num)', '\App\Modules\Notification\Controllers\NotificationController::delete/$1');
    $routes->get('check-new', '\App\Modules\Notification\Controllers\NotificationController::checkNew');
    $routes->post('check-new', '\App\Modules\Notification\Controllers\NotificationController::checkNew');
    $routes->post('test-push', '\App\Modules\Notification\Controllers\NotificationController::testPush');
    $routes->get('clear-all', '\App\Modules\Notification\Controllers\NotificationController::clearAll');
    $routes->post('clear-all', '\App\Modules\Notification\Controllers\NotificationController::clearAll');
});
