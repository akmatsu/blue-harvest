<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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
      $path = $file->store('public/uploads');

      $image = new Image();
      $image->user_id = Auth::id();
      $image->name = $file->getClientOriginalName();
      $image->path = $path;
      $image->mime_type = $file->getClientMimeType();
      $image->size = $file->getSize();
      $image->save();

      $paths[] = $path;
    }

    return response()->json(['uploaded' => true, 'paths' => $paths], 200);
  }
}
