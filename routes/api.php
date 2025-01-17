<?php

use App\Http\Controllers\RESTImageController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
  ->name('v1')
  ->group(function () {
    Route::prefix('images')
      ->name('images')
      ->group(function () {
        Route::get('/', [RESTImageController::class, 'index'])->name('index');
      });

    // Route::prefix('tags')
    //   ->name('tags.')
    //   ->group(function () {
    //     Route::get('/', [TagController::class, 'index'])->name('index');
    //     Route::post('/', [TagController::class, 'store'])
    //     ->name('store');
    //   });
  });
