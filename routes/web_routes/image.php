<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ImageController::class, 'index'])->name('index');

Route::prefix('images')
  ->name('images.')
  ->group(function () {
    Route::get('/{id}', [ImageController::class, 'view'])->name('view');

    Route::middleware(['auth', 'verified'])->group(function () {
      Route::get('/', [ImageController::class, 'manageImages'])->name('manage');
      Route::post('/', [ImageController::class, 'uploadImage'])->name('upload');
      Route::delete('/', [ImageController::class, 'bulkDelete'])->name(
        'delete.bulk'
      );
      Route::delete('/{id}', [ImageController::class, 'delete'])->name(
        'delete'
      );
      Route::post('/{id}', [ImageController::class, 'updateImage'])->name(
        'update'
      );

      Route::prefix('upload')
        ->name('upload.')
        ->group(function () {
          Route::get('/', [ImageController::class, 'uploadView'])->name(
            'index'
          );
          Route::get('/results', [
            ImageController::class,
            'uploadResultsView',
          ])->name('results');
        });
    });
  });
