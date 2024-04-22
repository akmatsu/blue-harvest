<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmartCropController;

Route::get('/blue-harvest', [SmartCropController::class, 'index'])->name(
  'blue-harvest'
);
