<?php

namespace App\Modules\Game\Config;

/** @var \CodeIgniter\Router\RouteCollection $routes */

// Public & Member Interactive Games Routes
$routes->get('mini-game', '\App\Modules\Game\Controllers\GameController::index');
$routes->get('mini-games', '\App\Modules\Game\Controllers\GameController::index');
$routes->get('games', '\App\Modules\Game\Controllers\GameController::index');

// Game #1: Exposure Triangle Simulator
$routes->get('mini-game/exposure-triangle', '\App\Modules\Game\Controllers\GameController::exposureTriangle');
$routes->get('games/exposure-triangle', '\App\Modules\Game\Controllers\GameController::exposureTriangle');

// Game #2: IDE Simulator (Code Editor & Live Compiler)
$routes->get('mini-game/ide-simulator', '\App\Modules\Game\Controllers\GameController::ideSimulator');
$routes->get('games/ide-simulator', '\App\Modules\Game\Controllers\GameController::ideSimulator');
$routes->get('mini-game/code-runner', '\App\Modules\Game\Controllers\GameController::ideSimulator');

// Game #3: Tapnich (Side-Scroller Drone Aerial)
$routes->get('mini-game/tapnich', '\App\Modules\Game\Controllers\GameController::tapnich');
$routes->get('games/tapnich', '\App\Modules\Game\Controllers\GameController::tapnich');
$routes->get('mini-game/drone-flight', '\App\Modules\Game\Controllers\GameController::tapnich');

// Game #4: Quiz MMC (Single & Multiplayer)
$routes->get('mini-game/quiz', '\App\Modules\Game\Controllers\GameController::quiz');
$routes->get('mini-game/trivia', '\App\Modules\Game\Controllers\GameController::quiz');
$routes->get('games/quiz', '\App\Modules\Game\Controllers\GameController::quiz');
$routes->get('mini-game/kuis', '\App\Modules\Game\Controllers\GameController::quiz');

// Game #5: Pelari Kalcer (Endless Skena Runner Ekskul Multimedia)
$routes->get('mini-game/pelari-kalcer', '\App\Modules\Game\Controllers\GameController::pelariKalcer');
$routes->get('games/pelari-kalcer', '\App\Modules\Game\Controllers\GameController::pelariKalcer');
$routes->get('mini-game/runner', '\App\Modules\Game\Controllers\GameController::pelariKalcer');

// Game #6: Studio Gambar & Mewarnai (Drawing & Coloring Studio)
$routes->get('mini-game/menggambar', '\App\Modules\Game\Controllers\GameController::menggambar');
$routes->get('games/menggambar', '\App\Modules\Game\Controllers\GameController::menggambar');
$routes->get('mini-game/gambar', '\App\Modules\Game\Controllers\GameController::menggambar');
$routes->get('mini-game/draw', '\App\Modules\Game\Controllers\GameController::menggambar');
$routes->get('mini-game/coloring', '\App\Modules\Game\Controllers\GameController::menggambar');

// Game #7: Aku Hacker (Cyber Hacker Simulator)
$routes->get('mini-game/aku-hacker', '\App\Modules\Game\Controllers\GameController::akuHacker');
$routes->get('games/aku-hacker', '\App\Modules\Game\Controllers\GameController::akuHacker');
$routes->get('mini-game/hacker', '\App\Modules\Game\Controllers\GameController::akuHacker');
$routes->get('mini-game/cyber-hacker', '\App\Modules\Game\Controllers\GameController::akuHacker');
$routes->get('mini-game/cyber', '\App\Modules\Game\Controllers\GameController::akuHacker');

// AJAX API for Game Progress / Score Recording
$routes->post('mini-game/api/record-score', '\App\Modules\Game\Controllers\GameController::recordScore');

