<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ImageController::class, 'index'])->name('index');

Route::prefix('images')
  ->name('images.')
  ->group(function () {
    Route::get('/view/{id}', [ImageController::class, 'view'])->name('view');

    Route::get('/download/{id}', [ImageController::class, 'download'])->name(
      'download'
    );
    Route::get('/download/optimized/{id}', [
      ImageController::class,
      'downloadOptimized',
    ])->name('download.optimized');

    Route::middleware(['auth', 'verified'])->group(function () {
      Route::get('/', [ImageController::class, 'manage'])->name('manage');
      Route::get('/manage/{id}', [ImageController::class, 'manageImage'])->name(
        'manageImage'
      );
      Route::post('/', [ImageController::class, 'upload'])->name('upload');
      Route::delete('/', [ImageController::class, 'bulkDelete'])->name(
        'delete.bulk'
      );
      Route::delete('/{id}', [ImageController::class, 'delete'])->name(
        'delete'
      );
      Route::patch('/{id}', [ImageController::class, 'updateImage'])->name(
        'update'
      );
    });
  });

Route::middleware(['auth', 'verified'])
  ->prefix('upload')
  ->name('upload.')
  ->group(function () {
    Route::get('/', [ImageController::class, 'uploadView'])->name('index');
    Route::get('/results', [ImageController::class, 'uploadResultsView'])->name(
      'results'
    );
  });
