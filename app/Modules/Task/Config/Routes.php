<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Member tasks & submissions
    $routes->get('member/tasks', '\App\Modules\Task\Controllers\TaskController::myTasks');
    $routes->get('member/tasks/submit/(:num)', '\App\Modules\Task\Controllers\TaskController::submitForm/$1');
    $routes->post('member/tasks/submit/(:num)', '\App\Modules\Task\Controllers\TaskController::submitStore/$1');
    $routes->get('member/tasks/delete-attachment/(:num)', '\App\Modules\Task\Controllers\TaskController::deleteAttachment/$1');
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
    $routes->match(['get', 'post'], 'tasks/duplicate/(:num)', '\App\Modules\Task\Controllers\TaskController::duplicate/$1');
    $routes->get('tasks/detail/(:num)', '\App\Modules\Task\Controllers\TaskController::detail/$1');
    $routes->get('tasks/submission/(:num)', '\App\Modules\Task\Controllers\TaskController::submissionDetail/$1');
    $routes->post('tasks/evaluate/(:num)', '\App\Modules\Task\Controllers\TaskController::evaluate/$1');
    $routes->post('tasks/evaluate-assignee/(:num)', '\App\Modules\Task\Controllers\TaskController::evaluateAssigneeDirectly/$1');
    $routes->post('tasks/bulk-evaluate/(:num)', '\App\Modules\Task\Controllers\TaskController::bulkEvaluate/$1');
    $routes->post('tasks/quick-grade/(:num)', '\App\Modules\Task\Controllers\TaskController::quickGrade/$1');
    $routes->post('tasks/update-status/(:num)', '\App\Modules\Task\Controllers\TaskController::quickUpdateStatus/$1');
    $routes->post('tasks/update-priority/(:num)', '\App\Modules\Task\Controllers\TaskController::quickUpdatePriority/$1');
    $routes->post('tasks/update-assignee-status/(:num)', '\App\Modules\Task\Controllers\TaskController::quickUpdateAssigneeStatus/$1');
    $routes->post('tasks/comment/(:num)', '\App\Modules\Task\Controllers\TaskController::postComment/$1');
});
