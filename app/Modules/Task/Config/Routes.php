<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Member tasks & submissions
    $routes->get('member/tasks', '\App\Modules\Task\Controllers\TaskController::myTasks');
    $routes->get('member/tasks/submit/(:num)', '\App\Modules\Task\Controllers\TaskController::submitForm/$1');
    $routes->post('member/tasks/submit/(:num)', '\App\Modules\Task\Controllers\TaskController::submitStore/$1');
    $routes->post('member/tasks/comment/(:num)', '\App\Modules\Task\Controllers\TaskController::postComment/$1');
});

$routes->group('admin', ['filter' => ['auth', 'role:superadmin,pembina,bph']], static function ($routes) {
    // Admin tasks CMS
    $routes->get('tasks', '\App\Modules\Task\Controllers\TaskController::index');
    $routes->get('tasks/create', '\App\Modules\Task\Controllers\TaskController::create');
    $routes->post('tasks/store', '\App\Modules\Task\Controllers\TaskController::store');
    $routes->get('tasks/edit/(:num)', '\App\Modules\Task\Controllers\TaskController::edit/$1');
    $routes->post('tasks/update/(:num)', '\App\Modules\Task\Controllers\TaskController::update/$1');
    $routes->get('tasks/delete/(:num)', '\App\Modules\Task\Controllers\TaskController::delete/$1');
    $routes->get('tasks/detail/(:num)', '\App\Modules\Task\Controllers\TaskController::detail/$1');
    $routes->post('tasks/evaluate/(:num)', '\App\Modules\Task\Controllers\TaskController::evaluate/$1');
    $routes->post('tasks/update-status/(:num)', '\App\Modules\Task\Controllers\TaskController::quickUpdateStatus/$1');
    $routes->post('tasks/update-priority/(:num)', '\App\Modules\Task\Controllers\TaskController::quickUpdatePriority/$1');
    $routes->post('tasks/update-assignee-status/(:num)', '\App\Modules\Task\Controllers\TaskController::quickUpdateAssigneeStatus/$1');
    $routes->post('tasks/comment/(:num)', '\App\Modules\Task\Controllers\TaskController::postComment/$1');
});
