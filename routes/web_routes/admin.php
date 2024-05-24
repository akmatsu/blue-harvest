<?php

use App\Http\Controllers\FlagController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
  Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
      Route::prefix('images')
        ->name('images.')
        ->group(function () {
          Route::get('/', [ImageController::class, 'adminManageImages'])
            ->middleware('can:edit images')
            ->name('index');
          Route::delete('/', [ImageController::class, 'adminBulkDelete'])
            ->middleware('can:delete images')
            ->name('delete.bulk');
          Route::patch('/{id}', [ImageController::class, 'adminUpdateImage'])
            ->middleware('can:edit images')
            ->name('view');
          Route::delete('/{id}', [ImageController::class, 'adminDelete'])
            ->middleware('can:delete images')
            ->name('delete');
          Route::get('/edit', [ImageController::class, 'adminImageEditView'])
            ->middleware('can:edit images')
            ->name('edit');
        });
      Route::prefix('users')
        ->name('users.')
        ->group(function () {
          Route::get('/', [UserController::class, 'index'])
            ->middleware('can:view users')
            ->name('index');
          Route::post('/', [UserController::class, 'create'])
            ->middleware('can:edit users')
            ->name('create');
          Route::delete('/', [UserController::class, 'deleteBulk'])
            ->middleware('can:delete users')
            ->name('delete.bulk');
          Route::get('/{id}', [UserController::class, 'view'])
            ->middleware('can:view users')
            ->name('view');
          Route::patch('/{id}', [UserController::class, 'update'])
            ->middleware('can:edit users')
            ->name('edit');
          route::delete('/{id}', [UserController::class, 'delete'])
            ->middleware('can:delete users')
            ->name('delete');
        });
      Route::prefix('flags')
        ->name('flags.')
        ->group(function () {
          Route::get('/', [FlagController::class, 'index'])
            ->middleware('can:view flags')
            ->name('index');
          Route::get('/{id}', [FlagController::class, 'show'])
            ->middleware('can:view flags')
            ->name('show');
          Route::delete('/{id}', [FlagController::class, 'dismiss'])
            ->middleware('can:delete flags')
            ->name('dismiss');
          Route::delete('/{id}/flaggable', [
            FlagController::class,
            'deleteFlaggable',
          ])
            ->middleware('can:edit flags')
            ->name('delete');
          Route::patch('/{id}/flaggable', [
            FlagController::class,
            'restrictFlaggable',
          ])
            ->middleware('can:edit flags')
            ->name('restrict');
          Route::post('/{id}/restrict', [
            FlagController::class,
            'restrictFlaggable',
          ])
            ->middleware('can:edit flags')
            ->name('restrict');
          Route::post('/{id}/lift-restriction', [
            FlagController::class,
            'liftFlaggableRestriction',
          ])
            ->middleware('can:edit flags')
            ->name('lift');
        });
    });
});
