<?php

namespace App\Modules\Information\Config;

/**
 * Information Module Routes
 */

// Member Routes
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('informasi', '\App\Modules\Information\Controllers\InformationController::index');
    $routes->post('informasi/mark-read/(:num)', '\App\Modules\Information\Controllers\InformationController::markAsRead/$1');
});

// Admin Routes (superadmin, pembina, bph)
$routes->group('admin', ['filter' => ['auth', 'role:superadmin,pembina,bph']], static function ($routes) {
    $routes->get('informasi', '\App\Modules\Information\Controllers\InformationController::adminIndex');
    $routes->get('informasi/create', '\App\Modules\Information\Controllers\InformationController::create');
    $routes->post('informasi/store', '\App\Modules\Information\Controllers\InformationController::store');
    $routes->get('informasi/edit/(:num)', '\App\Modules\Information\Controllers\InformationController::edit/$1');
    $routes->post('informasi/update/(:num)', '\App\Modules\Information\Controllers\InformationController::update/$1');
    $routes->get('informasi/delete/(:num)', '\App\Modules\Information\Controllers\InformationController::delete/$1');
    $routes->post('informasi/toggle-popup/(:num)', '\App\Modules\Information\Controllers\InformationController::togglePopup/$1');
});
