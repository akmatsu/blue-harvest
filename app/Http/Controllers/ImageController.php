<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Intervention\Image\Laravel\Facades\Image as ImageFacade;

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
      $dbImage = new Image();
      
      $uniqueFolder = generateUniqueFolder();
      $path = storeBaseImage($uniqueFolder, $file);
      
      $dbImage->user_id = Auth::id();
      $dbImage->name = $file->getClientOriginalName();
      $dbImage->path = $path;
      $dbImage->mime_type = Storage::mimeType($path);
      $dbImage->size = Storage::size($path);
      $dbImage->url = Storage::url($path);
      $dbImage->folder_name = $uniqueFolder;
      $imageDetails = getimagesize($file->getRealPath());
      $dbImage->width = $imageDetails[0];
      $dbImage->height = $imageDetails[1];

      $dbImage->save();

      $storedImage = Storage::get($path);

      $sizes = [
        'small' => ['width' => 500, 'height' => 500],
        'medium' => ['width' => 1000, 'height' => 1000],
        'large' => ['width' => 1920, 'height' => 1920],
      ];

      foreach ($sizes as $size => $dims) {
        $image = ImageFacade::read($storedImage);
        $resizedImage = $image->scaleDown($dims['width'], $dims['height']);
        $filePath = $uniqueFolder . 'optimized_images/';
        $storePath = storeOptimizedImage($filePath, $resizedImage);
        $url = Storage::url($storePath);
        $paths[$size] = $url;
        
        Log::info($storePath);
        $dbImage->optimizedImages()->create([
          'image_id' => $dbImage->id,
          'size' => $size,
          'path' => $storePath,
          'url' => $url,
          'width' => $resizedImage->size()->width(),
          'height' => $resizedImage->size()->height(),
          'file_size' => Storage::size($storePath),
        ]);
      }

      $paths[] = $path;
    }

    return response()->json(['uploaded' => true, 'paths' => $paths], 200);
  }

  public function view($id)
  {
    $image = Image::findOrFail($id);
    $optimizedImages = $image->predefinedImages();
    $imageData = $image->toArray();
    $imageData['optimizedImages'] = $optimizedImages;

    return Inertia::render('Image/View', ['image' => $imageData]);
  }

  public function edit($id)
  {
    $image = Image::findOrFail($id);
    return Inertia::render('Image/Edit', ['image' => $image]);
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
