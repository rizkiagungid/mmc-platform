<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('admin', ['filter' => ['auth', 'role:superadmin,pembina,bph']], static function ($routes) {
    $routes->get('meetings', '\App\Modules\Meeting\Controllers\MeetingController::index');
    $routes->get('meetings/create', '\App\Modules\Meeting\Controllers\MeetingController::create');
    $routes->post('meetings/store', '\App\Modules\Meeting\Controllers\MeetingController::store');
    $routes->get('meetings/edit/(:num)', '\App\Modules\Meeting\Controllers\MeetingController::edit/$1');
    $routes->post('meetings/update/(:num)', '\App\Modules\Meeting\Controllers\MeetingController::update/$1');
    $routes->get('meetings/delete/(:num)', '\App\Modules\Meeting\Controllers\MeetingController::delete/$1');
    $routes->get('meetings/activate/(:num)', '\App\Modules\Meeting\Controllers\MeetingController::activate/$1');
    $routes->get('meetings/complete/(:num)', '\App\Modules\Meeting\Controllers\MeetingController::complete/$1');
    $routes->get('meetings/qr/(:num)', '\App\Modules\Meeting\Controllers\MeetingController::qrPoster/$1');
});
