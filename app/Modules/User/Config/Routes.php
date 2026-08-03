<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('profile', '\App\Modules\User\Controllers\UserController::profile');
    $routes->post('profile', '\App\Modules\User\Controllers\UserController::updateProfile');
});

$routes->group('admin', ['filter' => ['auth', 'role:superadmin,pembina,bph']], static function ($routes) {
    $routes->get('users', '\App\Modules\User\Controllers\UserController::index');
    $routes->get('users/create', '\App\Modules\User\Controllers\UserController::create');
    $routes->post('users/store', '\App\Modules\User\Controllers\UserController::store');
    $routes->get('users/edit/(:num)', '\App\Modules\User\Controllers\UserController::edit/$1');
    $routes->post('users/update/(:num)', '\App\Modules\User\Controllers\UserController::update/$1');
    $routes->get('users/delete/(:num)', '\App\Modules\User\Controllers\UserController::delete/$1');
    $routes->get('users/regenerate-qr/(:num)', '\App\Modules\User\Controllers\UserController::regenerateQr/$1');
    $routes->get('users/qr/(:segment)', '\App\Modules\User\Controllers\UserController::showQr/$1');
});
