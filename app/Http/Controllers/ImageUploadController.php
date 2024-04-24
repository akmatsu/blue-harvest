<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ImageUploadController extends Controller
{
  public function index()
  {
    return Inertia::render('ImageUpload');
  }
  public function upload(Request $request): JsonResponse
  {
    $request->validate([
      'files' => 'required|array',
      'files.*' => 'file',
    ]);

    $files = $request->file('files');
    $paths = [];

    foreach ($files as $file) {
      $path = $file->store('uploads');
      $paths[] = $path;
    }

    return response()->json(['uploaded' => true, 'paths' => $paths], 200);
  }
}
