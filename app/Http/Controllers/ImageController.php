<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ImageController extends Controller
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

  public function manageImages()
  {
    $user = Auth::user();

    if ($user) {
      return Inertia::render('ManageImages', ['images' => $user->images]);
    }
  }

  public function delete($id)
  {
    $user_id = Auth::id();
    $image = Image::findOrFail($id)->where('user_id', $user_id);

    if ($image) {
      $image->delete();
      return response('Image successfully deleted', 202);
    }

    return response('Image does not exist or has already been deleted', 204);
  }

  public function bulkDelete(Request $request)
  {
    $validated = $request->validate([
      'ids' => 'required|array|min:1',
      'ids.*' => 'integer:exists:images,id',
    ]);
    $user_id = Auth::id();
    $images = Image::all()
      ->whereIn('id', $validated['ids'])
      ->where('user_id', $user_id);

    foreach ($images as $image) {
      $image->delete();
    }

    return response('Images successfully deleted', 202);
  }
}
