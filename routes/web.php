<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebPageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// TODO: Remove the dashboard view or change it to something else.
// TODO: Implement the image manage view for image owners.
// TODO: Implement the user management view for admins.

Route::get('/', function () {
  return Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
  ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/browse', [ImageController::class, 'index'])->name(
    'browse-images'
  );
  Route::get('/upload', [ImageController::class, 'uploadView'])->name(
    'image-upload'
  );
  Route::get('/images', [ImageController::class, 'manageImages'])->name(
    'image-manage'
  );
  Route::post('/images', [ImageController::class, 'uploadImage'])->name(
    'image.upload'
  );
  Route::delete('/images', [ImageController::class, 'bulkDelete'])->name(
    'image-delete-bulk'
  );
  Route::get('/images/{id}', [ImageController::class, 'view'])->name(
    'image-view'
  );
  Route::get('/images/{id}/edit', [ImageController::class, 'edit'])->name(
    'image-edit'
  );
  Route::delete('/images/{id}', [ImageController::class, 'delete'])->name(
    'image-delete'
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
