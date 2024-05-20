<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\PopularSearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ImageController::class, 'index'])->name('browse-images');
Route::get('/popular-searches', [
  PopularSearchController::class,
  'getPopularSearches',
]);

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

  Route::get('/admin/images', [ImageController::class, 'adminManageImages'])
    ->middleware('can:edit images')
    ->name('admin-image-manage');

  Route::patch('/admin/images/{id}', [
    ImageController::class,
    'adminUpdateImage',
  ])
    ->middleware('can:edit images')
    ->name('admin-image-update');

  Route::get('/admin/images/edit', [
    ImageController::class,
    'adminImageEditView',
  ])
    ->middleware('can:edit images')
    ->name('admin-image-update');

  Route::delete('/admin/images', [
    ImageController::class,
    'adminBulkDelete',
  ])->middleware('can:delete images');

  Route::delete('/admin/images/{id}', [
    ImageController::class,
    'adminDelete',
  ])->middleware('can:delete images');

  Route::get('/admin/users', [UserController::class, 'index'])
    ->middleware('can:view users')
    ->name('admin.users');

  Route::get('/admin/users/{id}', [UserController::class, 'view'])
    ->middleware('can:view users')
    ->name('admin.users.view');

  Route::patch('/admin/users/{id}', [UserController::class, 'update'])
    ->middleware('can:edit users')
    ->name('admin.users.edit');

  Route::post('/admin/users/', [UserController::class, 'create'])
    ->middleware('can:edit users')
    ->name('admin.users.create');

  route::delete('/admin/users/{id}', [UserController::class, 'delete'])
    ->middleware('can:delete users')
    ->name('admin.users.delete');

  route::delete('/admin/users', [UserController::class, 'deleteBulk'])
    ->middleware('can:delete users')
    ->name('admin.users.delete.bulk');
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
