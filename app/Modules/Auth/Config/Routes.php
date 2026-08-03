<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('login', '\App\Modules\Auth\Controllers\AuthController::login');
$routes->post('login', '\App\Modules\Auth\Controllers\AuthController::attemptLogin');
$routes->get('register', '\App\Modules\Auth\Controllers\AuthController::register');
$routes->post('register', '\App\Modules\Auth\Controllers\AuthController::attemptRegister');
$routes->get('logout', '\App\Modules\Auth\Controllers\AuthController::logout');
