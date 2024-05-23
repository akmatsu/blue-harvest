<?php

use App\Http\Controllers\FlagController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PopularSearchController;

use Illuminate\Support\Facades\Route;

Route::get('/popular-searches', [
  PopularSearchController::class,
  'getPopularSearches',
])->name('searches');

Route::post('/flags', [FlagController::class, 'store'])
  ->middleware(['auth, verified'])
  ->name('flags.store');

require __DIR__ . '/auth.php';
require __DIR__ . '/web_routes/image.php';
require __DIR__ . '/web_routes/admin.php';
require __DIR__ . '/web_routes/profile.php';
