<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ImageUploadController extends Controller
{
  public function index()
  {
    return Inertia::render('ImageUpload');
  }
  public function upload(): JsonResponse
  {
    Log::info('ran');
    return response()->json('Yay!');
  }
}
