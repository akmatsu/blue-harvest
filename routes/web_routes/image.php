<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ImageController::class, 'index'])->name('images');

Route::get('/images/{id}', [ImageController::class, 'view'])->name(
  'images.view'
);

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/upload', [ImageController::class, 'uploadView'])->name(
    'images.upload'
  );
  Route::get('/upload/results', [
    ImageController::class,
    'uploadResultsView',
  ])->name('images.upload.results');
  Route::get('/images', [ImageController::class, 'manageImages'])->name(
    'images.manage'
  );
  Route::post('/images', [ImageController::class, 'uploadImage'])->name(
    'images.upload'
  );
  Route::delete('/images', [ImageController::class, 'bulkDelete'])->name(
    'images.delete.bulk'
  );
  Route::delete('/images/{id}', [ImageController::class, 'delete'])->name(
    'images.delete'
  );
  Route::post('/images/{id}', [ImageController::class, 'updateImage'])->name(
    'images.update'
  );
});
