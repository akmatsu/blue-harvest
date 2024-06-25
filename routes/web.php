<?php

use App\Http\Controllers\FlagController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PopularSearchController;

use Illuminate\Support\Facades\Route;

Route::get('/popular-searches', [
  PopularSearchController::class,
  'getPopularSearches',
])->name('searches');

Route::post('/flags', [FlagController::class, 'store'])
  ->middleware(['auth', 'verified'])
  ->name('flags.store');

Route::get('/notifications/unread', [
  NotificationController::class,
  'unread',
])->name('notifications.unread');

Route::post('/notifications/read/{id}', [
  NotificationController::class,
  'read',
])->name('notifications.read');

Route::post('/notifications/read', [
  NotificationController::class,
  'readAll',
])->name('notifications.read.all');

require __DIR__ . '/auth.php';
require __DIR__ . '/web_routes/image.php';
require __DIR__ . '/web_routes/admin.php';
require __DIR__ . '/web_routes/profile.php';
