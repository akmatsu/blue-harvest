<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/admin/images', [ImageController::class, 'adminManageImages'])
    ->middleware('can:edit images')
    ->name('admin.images');

  Route::patch('/admin/images/{id}', [
    ImageController::class,
    'adminUpdateImage',
  ])
    ->middleware('can:edit images')
    ->name('admin.images.view');

  Route::get('/admin/images/edit', [
    ImageController::class,
    'adminImageEditView',
  ])
    ->middleware('can:edit images')
    ->name('admin.images.edit');

  Route::delete('/admin/images', [ImageController::class, 'adminBulkDelete'])
    ->middleware('can:delete images')
    ->name('admin.images.delete.bulk');

  Route::delete('/admin/images/{id}', [ImageController::class, 'adminDelete'])
    ->middleware('can:delete images')
    ->name('admin.images.delete');

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
