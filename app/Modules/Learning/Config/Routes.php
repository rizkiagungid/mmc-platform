<?php

namespace App\Modules\Learning\Config;

// Admin CMS Learning Routes
$routes->group('admin/learning', ['filter' => 'role:superadmin,pembina,bph'], function ($routes) {
    $routes->get('/', '\App\Modules\Learning\Controllers\LearningCmsController::index');
    $routes->get('create', '\App\Modules\Learning\Controllers\LearningCmsController::create');
    $routes->post('store', '\App\Modules\Learning\Controllers\LearningCmsController::store');
    $routes->get('edit/(:num)', '\App\Modules\Learning\Controllers\LearningCmsController::edit/$1');
    $routes->post('update/(:num)', '\App\Modules\Learning\Controllers\LearningCmsController::update/$1');
    $routes->get('delete/(:num)', '\App\Modules\Learning\Controllers\LearningCmsController::delete/$1');
    $routes->get('restore/(:num)', '\App\Modules\Learning\Controllers\LearningCmsController::restore/$1');
    $routes->get('purge/(:num)', '\App\Modules\Learning\Controllers\LearningCmsController::purge/$1');
    $routes->post('bulk-action', '\App\Modules\Learning\Controllers\LearningCmsController::bulkAction');
});

// Member Portal Learning Routes (Inside Dashboard Master Layout)
$routes->group('member/learning', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', '\App\Modules\Learning\Controllers\MemberLearningController::index');
    $routes->get('(:segment)', '\App\Modules\Learning\Controllers\MemberLearningController::detail/$1');
});

// Public Learning Center Routes (Outside Dashboard Master Layout)
$routes->get('materi', '\App\Modules\Learning\Controllers\PublicLearningController::index');
$routes->get('materi/(:segment)', '\App\Modules\Learning\Controllers\PublicLearningController::detail/$1');
