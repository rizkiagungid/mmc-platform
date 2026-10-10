<?php

namespace App\Modules\Tools\Config;

/** @var \CodeIgniter\Router\RouteCollection $routes */

// All Tools Editor routes are strictly protected behind the 'auth' filter.
// Unauthenticated / public / guest users will be redirected to login.
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Hub Catalog
    $routes->get('tools-editor', '\App\Modules\Tools\Controllers\ToolsController::index');
    $routes->get('tools', '\App\Modules\Tools\Controllers\ToolsController::index');
    $routes->get('admin/tools-editor', '\App\Modules\Tools\Controllers\ToolsController::index');
    $routes->get('member/tools-editor', '\App\Modules\Tools\Controllers\ToolsController::index');

    // Interactive Tool Studio Workspace
    $routes->get('tools-editor/(:segment)', '\App\Modules\Tools\Controllers\ToolsController::tool/$1');
    $routes->get('tools/(:segment)', '\App\Modules\Tools\Controllers\ToolsController::tool/$1');
});
