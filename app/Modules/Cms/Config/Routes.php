<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('admin/cms', ['filter' => ['auth', 'role:superadmin,admin,pembina,bph']], static function ($routes) {
    // WCMS Homepage & Hero Builder
    $routes->get('builder', '\App\Modules\Cms\Controllers\CmsController::index');
    $routes->post('sections/update', '\App\Modules\Cms\Controllers\CmsController::updateSections');
    $routes->post('hero/update', '\App\Modules\Cms\Controllers\CmsController::updateHero');
    $routes->post('hero/video/save', '\App\Modules\Cms\Controllers\CmsController::saveHeroVideo');
    $routes->get('hero/video/delete/(:num)', '\App\Modules\Cms\Controllers\CmsController::deleteHeroVideo/$1');
    $routes->post('stats/save', '\App\Modules\Cms\Controllers\CmsController::saveStat');
    $routes->get('stats/delete/(:num)', '\App\Modules\Cms\Controllers\CmsController::deleteStat/$1');

    // Centralized Media Library
    $routes->get('media', '\App\Modules\Cms\Controllers\MediaLibraryController::index');
    $routes->post('media/upload', '\App\Modules\Cms\Controllers\MediaLibraryController::upload');
    $routes->get('media/delete/(:num)', '\App\Modules\Cms\Controllers\MediaLibraryController::delete/$1');
    $routes->get('media/api-list', '\App\Modules\Cms\Controllers\MediaLibraryController::apiList');

    // Divisions & Learning Programs (CRUD)
    $routes->get('divisions', '\App\Modules\Cms\Controllers\DivisionCmsController::index');
    $routes->post('divisions/store', '\App\Modules\Cms\Controllers\DivisionCmsController::storeDivision');
    $routes->post('divisions/update/(:num)', '\App\Modules\Cms\Controllers\DivisionCmsController::updateDivision/$1');
    $routes->get('divisions/delete/(:num)', '\App\Modules\Cms\Controllers\DivisionCmsController::deleteDivision/$1');

    $routes->post('divisions/programs/store', '\App\Modules\Cms\Controllers\DivisionCmsController::storeProgram');
    $routes->post('divisions/programs/update/(:num)', '\App\Modules\Cms\Controllers\DivisionCmsController::updateProgram/$1');
    $routes->get('divisions/programs/delete/(:num)', '\App\Modules\Cms\Controllers\DivisionCmsController::deleteProgram/$1');

    // Portfolios & Multi-Contributors (CRUD)
    $routes->get('portfolios', '\App\Modules\Cms\Controllers\PortfolioCmsController::index');
    $routes->post('portfolios/store', '\App\Modules\Cms\Controllers\PortfolioCmsController::store');
    $routes->post('portfolios/update/(:num)', '\App\Modules\Cms\Controllers\PortfolioCmsController::update/$1');
    $routes->get('portfolios/delete/(:num)', '\App\Modules\Cms\Controllers\PortfolioCmsController::delete/$1');

    // Gallery & Activity Documentation (CRUD)
    $routes->get('gallery', '\App\Modules\Cms\Controllers\GalleryCmsController::index');
    $routes->post('gallery/store', '\App\Modules\Cms\Controllers\GalleryCmsController::store');
    $routes->post('gallery/update/(:num)', '\App\Modules\Cms\Controllers\GalleryCmsController::update/$1');
    $routes->get('gallery/delete/(:num)', '\App\Modules\Cms\Controllers\GalleryCmsController::delete/$1');
    $routes->post('gallery/delete-media/(:num)', '\App\Modules\Cms\Controllers\GalleryCmsController::deleteMedia/$1');

    // Achievements & Multi-Member Teams (CRUD)
    $routes->get('achievements', '\App\Modules\Cms\Controllers\AchievementCmsController::index');
    $routes->post('achievements/store', '\App\Modules\Cms\Controllers\AchievementCmsController::store');
    $routes->post('achievements/update/(:num)', '\App\Modules\Cms\Controllers\AchievementCmsController::update/$1');
    $routes->get('achievements/delete/(:num)', '\App\Modules\Cms\Controllers\AchievementCmsController::delete/$1');

    // History & Timelines
    $routes->get('history', '\App\Modules\Cms\Controllers\HistoryCmsController::index');
    $routes->post('history/save', '\App\Modules\Cms\Controllers\HistoryCmsController::saveHistory');
    $routes->post('history/missions/save', '\App\Modules\Cms\Controllers\HistoryCmsController::saveMission');
    $routes->post('history/missions/update/(:num)', '\App\Modules\Cms\Controllers\HistoryCmsController::updateMission/$1');
    $routes->get('history/missions/delete/(:num)', '\App\Modules\Cms\Controllers\HistoryCmsController::deleteMission/$1');
    $routes->post('history/timelines/save', '\App\Modules\Cms\Controllers\HistoryCmsController::saveTimeline');
    $routes->post('history/timelines/update/(:num)', '\App\Modules\Cms\Controllers\HistoryCmsController::updateTimeline/$1');
    $routes->get('history/timelines/delete/(:num)', '\App\Modules\Cms\Controllers\HistoryCmsController::deleteTimeline/$1');

    // Organizational Chart Structure (CRUD)
    $routes->get('structure', '\App\Modules\Cms\Controllers\OrgCmsController::index');
    $routes->post('structure/store', '\App\Modules\Cms\Controllers\OrgCmsController::store');
    $routes->post('structure/update/(:num)', '\App\Modules\Cms\Controllers\OrgCmsController::update/$1');
    $routes->get('structure/delete/(:num)', '\App\Modules\Cms\Controllers\OrgCmsController::delete/$1');

    // Contact Messages & Admin Chat Reply (Accessible by Admin and Member)
    // FAQ Management (CRUD)
    $routes->get('faqs', '\App\Modules\Cms\Controllers\FaqCmsController::index');
    $routes->post('faqs/store', '\App\Modules\Cms\Controllers\FaqCmsController::store');
    $routes->post('faqs/update/(:num)', '\App\Modules\Cms\Controllers\FaqCmsController::update/$1');
    $routes->get('faqs/delete/(:num)', '\App\Modules\Cms\Controllers\FaqCmsController::delete/$1');

    // Delete Messages (Admin only)
    $routes->get('messages/delete/(:num)', '\App\Modules\Cms\Controllers\CmsController::deleteMessage/$1');
});

// Feedback, Kritik & Saran, and Contact Messages (Accessible by Admin and Member)
$routes->group('admin/cms', ['filter' => ['auth', 'role:superadmin,pembina,bph,member,anggota']], static function ($routes) {
    $routes->get('messages', '\App\Modules\Cms\Controllers\CmsController::contactMessages');
    $routes->get('messages/chat/(:num)', '\App\Modules\Cms\Controllers\CmsController::getChatThread/$1');
    $routes->post('messages/reply/(:num)', '\App\Modules\Cms\Controllers\CmsController::replyMessage/$1');
    $routes->post('messages/store', '\App\Modules\Cms\Controllers\CmsController::storeFeedback');
});

// Public Contact Form & Live Chat Submissions
$routes->post('contact/submit', '\App\Modules\Cms\Controllers\CmsController::submitContact');
$routes->post('contact/chat-reply', '\App\Modules\Cms\Controllers\CmsController::visitorChatReply');
$routes->get('contact/chat-poll/(:num)', '\App\Modules\Cms\Controllers\CmsController::pollVisitorChat/$1');
