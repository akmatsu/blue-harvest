<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ImageUploadController extends Controller
{
  public function index()
  {
    $images = Image::all();
    return Inertia::render('Browse', ['images' => $images]);
  }
  public function uploadView()
  {
    return Inertia::render('ImageUpload');
  }
  public function uploadImage(Request $request): JsonResponse
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
      $image->url = Storage::url($path);

      // Get image dimensions
      $imageDetails = getimagesize($file->getRealPath());
      $image->width = $imageDetails[0];
      $image->height = $imageDetails[1];

      $image->save();

      $paths[] = $path;
    }

    return response()->json(['uploaded' => true, 'paths' => $paths], 200);
  }

  public function view($id)
  {
    $image = Image::findOrFail($id);
    return Inertia::render('ViewImage', ['image' => $image]);
  }
}
