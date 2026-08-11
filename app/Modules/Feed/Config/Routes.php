<?php

namespace App\Modules\Feed\Config;

$routes = service('routes');

$routes->group('feed', ['filter' => 'auth'], static function ($routes) {
    $routes->get('', '\App\Modules\Feed\Controllers\FeedController::index');
    $routes->get('/', '\App\Modules\Feed\Controllers\FeedController::index');
    $routes->post('create', '\App\Modules\Feed\Controllers\FeedController::createPost');
    $routes->get('delete/(:num)', '\App\Modules\Feed\Controllers\FeedController::deletePost/$1');
    $routes->post('delete/(:num)', '\App\Modules\Feed\Controllers\FeedController::deletePost/$1');
    $routes->post('like/(:num)', '\App\Modules\Feed\Controllers\FeedController::toggleLike/$1');
    $routes->post('comment/(:num)', '\App\Modules\Feed\Controllers\FeedController::addComment/$1');
    $routes->get('delete-comment/(:num)', '\App\Modules\Feed\Controllers\FeedController::deleteComment/$1');
    $routes->post('delete-comment/(:num)', '\App\Modules\Feed\Controllers\FeedController::deleteComment/$1');
    $routes->get('follow/(:num)', '\App\Modules\Feed\Controllers\FeedController::toggleFollow/$1');
    $routes->post('follow/(:num)', '\App\Modules\Feed\Controllers\FeedController::toggleFollow/$1');
    $routes->get('user/(:num)', '\App\Modules\Feed\Controllers\FeedController::userWall/$1');
    $routes->get('followers/(:num)', '\App\Modules\Feed\Controllers\FeedController::getFollowers/$1');
    $routes->get('following/(:num)', '\App\Modules\Feed\Controllers\FeedController::getFollowing/$1');
    $routes->get('load-more', '\App\Modules\Feed\Controllers\FeedController::loadMore');
    $routes->get('check-new', '\App\Modules\Feed\Controllers\FeedController::checkNewPosts');
});
