<?php

use App\Http\Controllers\RESTImageController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
  ->name('v1')
  ->group(function () {
    Route::prefix('images')
      ->name('images')
      ->group(function () {
        Route::get('/', [RESTImageController::class, 'index'])->name('index');
      });
  });
