<?php

use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebPageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// TODO: Implement the image manage view for image owners.
// TODO: Implement the public browse view.
// TODO: Implement the wizard view.
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
  Route::get('/dashboard', [WebPageController::class, 'index'])->name(
    'dashboard'
  );
  Route::get('/upload', [ImageUploadController::class, 'index'])->name(
    'image-upload'
  );
  Route::post('/images', [ImageUploadController::class, 'upload'])->name(
    'image.upload'
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
