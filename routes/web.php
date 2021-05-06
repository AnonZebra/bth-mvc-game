<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\Fallback404Controller;
use App\Http\Controllers\BlackjackController;

Route::get('/', [HomeController::class, 'home']);

Route::get('/session', [SessionController::class, 'sessionInfo']);

Route::get('/session/destroy', [SessionController::class, 'sessionDestroy']);

Route::get('/debug', [DebugController::class, 'debug']);

Route::get('/blackjack', [BlackjackController::class, 'blackjackShow']);

Route::post('/blackjack', [BlackjackController::class, 'blackjackProcess']);

Route::fallback([Fallback404Controller::class, 'fallback']);

