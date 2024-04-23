<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// TODO: Implement the upload view.
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

Route::get('/dashboard', function () {
  return Inertia::render('Dashboard');
})
  ->middleware(['auth', 'verified'])
  ->name('dashboard');

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
