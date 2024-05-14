<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Intervention\Image\Laravel\Facades\Image as ImageFacade;

class ImageController extends Controller
{
  public function index(Request $request)
  {
    $images = Image::paginate(25); // Fetch 10 images per page
    if ($request->wantsJson()) {
      return response()->json($images);
    }
    return Inertia::render('Browse', ['images' => $images]);
  }

  public function uploadView()
  {
    return Inertia::render('ImageUpload');
  }

  public function uploadResultsView(Request $request)
  {
    $ids = $request->query('ids', []);
    $images = Image::whereIn('id', $ids)->with('tags')->get();
    $tags = Tag::all();

    return Inertia::render('ImageUploadResults', [
      'images' => $images,
      'tags' => $tags,
    ]);
  }

  public function uploadImage(Request $request)
  {
    $request->validate([
      'files' => 'required|array',
      'files.*' => 'file',
    ]);

    $files = $request->file('files');
    $ids = [];

    foreach ($files as $file) {
      $dbImage = new Image();

      $uniqueFolder = generateUniqueFolder();
      $path = storeBaseImage($uniqueFolder, $file);

      $this->populateImageData($dbImage, $file, $path, $uniqueFolder);
      $dbImage->save();

      $this->attachRandomTag($dbImage);

      $this->generateOptimizedImages($dbImage, $path, $uniqueFolder);

      $ids[] = $dbImage->id;
    }

    return redirect()->route('image-upload-results', ['ids' => $ids]);
  }

  public function updateImage(int $id, Request $request)
  {
    $request->validate([
      'tags' => 'array',
      'tags.*' => 'integer|exists:tags,id',
      'name' => 'string',
    ]);

    $image = Image::findOrFail($id);

    if ($request->has('name')) {
      $image->name = $request->input('name');
      $image->save();
    }

    if ($request->has('tags')) {
      $image->tags()->sync($request->input('tags'));
    }

    return back();
  }

  public function view($id)
  {
    $image = Image::with([
      'optimizedImages' => function ($query) {
        $query->whereIn('size', ['small', 'medium', 'large']);
      },
    ])->findOrFail($id);

    return Inertia::render('Image/View', ['image' => $image]);
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

    return redirect()->route('login');
  }

  public function delete($id)
  {
    $image = Image::where('id', $id)
      ->where('user_id', Auth::id())
      ->firstOrFail();

    $image->delete();
    return response('Image successfully deleted', 202);
  }

  public function bulkDelete(Request $request)
  {
    $validated = $request->validate([
      'ids' => 'required|array|min:1',
      'ids.*' => 'integer|exists:images,id',
    ]);

    $user_id = Auth::id();
    $images = Image::whereIn('id', $validated['ids'])
      ->where('user_id', $user_id)
      ->get();

    foreach ($images as $image) {
      $image->delete();
    }

    return response('Images successfully deleted', 202);
  }

  private function populateImageData($dbImage, $file, $path, $uniqueFolder)
  {
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
  }

  private function attachRandomTag($dbImage)
  {
    $randomTag = Tag::inRandomOrder()->first();
    if ($randomTag) {
      $dbImage->tags()->attach($randomTag->id);
    }
  }

  private function generateOptimizedImages($dbImage, $path, $uniqueFolder)
  {
    $storedImage = Storage::get($path);

    $sizes = [
      'small' => ['width' => 500, 'height' => 500],
      'medium' => ['width' => 1000, 'height' => 1000],
      'large' => ['width' => 1920, 'height' => 1920],
    ];

    foreach ($sizes as $size => $dims) {
      $image = ImageFacade::read($storedImage);

      if (
        $image->width() > $dims['width'] ||
        $image->height() > $dims['height']
      ) {
        $resizedImage = $image->scaleDown($dims['width'], $dims['height']);
        $filePath = $uniqueFolder . 'optimized_images/';
        $storePath = storeOptimizedImage($filePath, $resizedImage);
        $url = Storage::url($storePath);

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
    }
  }
}
