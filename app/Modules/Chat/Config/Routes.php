<?php

namespace App\Modules\Chat\Config;

$routes = service('routes');

$routes->group('inbox', ['filter' => 'auth'], static function ($routes) {
    $routes->get('', '\App\Modules\Chat\Controllers\ChatController::index');
    $routes->get('/', '\App\Modules\Chat\Controllers\ChatController::index');
    $routes->get('messages/(:num)', '\App\Modules\Chat\Controllers\ChatController::getMessages/$1');
    $routes->post('send/(:num)', '\App\Modules\Chat\Controllers\ChatController::sendMessage/$1');
    $routes->post('start-direct', '\App\Modules\Chat\Controllers\ChatController::startDirect');
    $routes->post('create-group', '\App\Modules\Chat\Controllers\ChatController::createGroup');
    $routes->get('delete-message/(:num)', '\App\Modules\Chat\Controllers\ChatController::deleteMessage/$1');
    $routes->post('delete-message/(:num)', '\App\Modules\Chat\Controllers\ChatController::deleteMessage/$1');
    $routes->get('delete-conv/(:num)', '\App\Modules\Chat\Controllers\ChatController::deleteConversation/$1');
    $routes->post('delete-conv/(:num)', '\App\Modules\Chat\Controllers\ChatController::deleteConversation/$1');
    $routes->get('leave-group/(:num)', '\App\Modules\Chat\Controllers\ChatController::leaveGroup/$1');
    $routes->post('leave-group/(:num)', '\App\Modules\Chat\Controllers\ChatController::leaveGroup/$1');
    $routes->post('update-group/(:num)', '\App\Modules\Chat\Controllers\ChatController::updateGroupInfo/$1');
    $routes->get('group-toggle-admin/(:num)/(:num)', '\App\Modules\Chat\Controllers\ChatController::toggleGroupAdmin/$1/$2');
    $routes->post('group-toggle-admin/(:num)/(:num)', '\App\Modules\Chat\Controllers\ChatController::toggleGroupAdmin/$1/$2');
    $routes->get('group-remove-member/(:num)/(:num)', '\App\Modules\Chat\Controllers\ChatController::removeGroupMember/$1/$2');
    $routes->post('group-remove-member/(:num)/(:num)', '\App\Modules\Chat\Controllers\ChatController::removeGroupMember/$1/$2');
    $routes->post('group-add-members/(:num)', '\App\Modules\Chat\Controllers\ChatController::addGroupMembers/$1');
});
