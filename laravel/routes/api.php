<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmartCropController;

Route::get('/blue-harvest', [SmartCropController::class, 'index'])
  ->middleware('throttle:120,1')


  
  ->name('blue-harvest');
