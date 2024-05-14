<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ImageController::class, 'index'])->name('browse-images');

Route::get('/images/{id}', [ImageController::class, 'view'])->name(
  'image-view'
);

Route::get('/images/{id}/edit', [ImageController::class, 'edit'])->name(
  'image-edit'
);

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/upload', [ImageController::class, 'uploadView'])->name(
    'image-upload'
  );
  Route::get('/upload/results', [
    ImageController::class,
    'uploadResultsView',
  ])->name('image-upload-results');
  Route::get('/images', [ImageController::class, 'manageImages'])->name(
    'image-manage'
  );
  Route::post('/images', [ImageController::class, 'uploadImage'])->name(
    'image.upload'
  );
  Route::delete('/images', [ImageController::class, 'bulkDelete'])->name(
    'image-delete-bulk'
  );
  Route::delete('/images/{id}', [ImageController::class, 'delete'])->name(
    'image-delete'
  );
  Route::post('/images/{id}', [ImageController::class, 'updateImage'])->name(
    'image-update'
  );
});

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name(
    'profile.edit'
  );
  Route::patch('/profile', [ProfileController::class, 'update'])->name(
    'profile.update'
  );
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name(
    'profile.destroy'
  );
});

require __DIR__ . '/auth.php';
