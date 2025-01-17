<?php

namespace App\Http\Controllers;

use App\Helpers\TypesenseHelper;
use App\Jobs\ProcessImage;
use App\Models\Image;
use App\Models\OptimizedImage;
use App\Models\Restriction;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Intervention\Image\Laravel\Facades\Image as ImageFacade;

class ImageController extends Controller
{
  protected $tsHelper;

  public function __construct()
  {
    $this->tsHelper = new TypesenseHelper();
  }

  public function index(Request $request)
  {
    $query = $request->input('query', '*');
    $limit = $request->input('count', 25);

    $filters = $this->tsHelper->extractFilters($query);
    $searchQuery = $this->tsHelper->stripFiltersFromQuery($query);
    $images = Image::search($searchQuery)->whereIn('status', ['public']);
    if ($filters) {
      $images->whereIn('tags', $filters);
    }

    if (!Auth::check()) {
      $images->where('is_restricted', 0);
    }

    $results = $images->paginate($limit);

    if ($query && $query !== '*' && $results->total() > 0) {
      $this->tsHelper->logSearchQuery($query);
    }

    if ($request->wantsJson()) {
      return response()->json($images);
    }

    return Inertia::render('Browse', ['images' => $results]);
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

    if ($images->isEmpty()) {
      return redirect()->route('images.manage');
    }

    return Inertia::render('ImageUploadResults', [
      'images' => $images,
      'tags' => $tags,
    ]);
  }

  public function adminImageEditView(Request $request)
  {
    $ids = $request->query('ids', []);
    $images = Image::whereIn('id', $ids)->with('tags')->get();
    $tags = Tag::all();

    if ($images->isEmpty()) {
      return redirect()->route('images.manage');
    }

    return Inertia::render('Admin/EditImages', [
      'images' => $images,
      'tags' => $tags,
    ]);
  }

  public function upload(Request $request)
  {
    $request->validate([
      'files' => 'required|array|max:25',
      'files.*' => 'file|mimes:jpg,jpeg,png,webp|max:102400',
    ]);

    $files = $request->file('files');
    $ids = [];

    foreach ($files as $file) {
      $dbImage = new Image();

      $uniqueFolder = generateUniqueFolder();
      $path = storeBaseImage($uniqueFolder, $file);

      $this->populateImageData($dbImage, $file, $path, $uniqueFolder);
      $dbImage->status = 'pending processing';
      $dbImage->save();
      $this->generateOptimizedImages($dbImage, $path, $uniqueFolder);

      ProcessImage::dispatch($dbImage, $file);

      $ids[] = $dbImage->id;
    }

    return redirect()->route('upload.results', ['ids' => $ids]);
  }

  public function download(int $id)
  {
    $image = Image::findOrFail($id);
    $filePath = $image->path;

    if (!Storage::exists($filePath)) {
      Log::error('File down not exist at path: ' . $filePath);
      return response()->json(['error' => 'File not found'], 404);
    }

    return Storage::download($filePath);
  }

  public function downloadOptimized(int $id)
  {
    $image = OptimizedImage::findOrFail($id);
    $filePath = $image->path;

    if (!Storage::exists($filePath)) {
      Log::error('File down not exist at path: ' . $filePath);
      return response()->json(['error' => 'File not found'], 404);
    }

    return Storage::download($filePath);
  }

  public function updateImage(int $id, Request $request)
  {
    $request->validate([
      'tags' => 'array',
      'tags.*' => 'string',
      'name' => 'string',
      'status' => 'string',
      'description' => 'nullable|string',
      'attribution' => 'string',
    ]);

    $image = Image::findOrFail($id);

    if ($request->has('name')) {
      $image->name = $request->input('name');
      $image->save();
    }

    if ($request->has('attribution')) {
      $image->attribution = $request->input('attribution');
    }

    if ($request->has('description')) {
      $image->description = $request->input('description');
    }

    if ($request->has('status')) {
      $image->status = $request->input('status');
    }

    if ($request->has('tags')) {
      $tagIds = [];
      foreach ($request->input('tags') as $tagName) {
        $tag = Tag::firstOrCreate(['name' => $tagName]);
        $tagIds[] = $tag->id;
      }

      $image->tags()->sync($tagIds);
      $image->save();
    }

    return back();
  }

  public function bulkUpdate(Request $request)
  {
    $validated = $request->validate([
      'ids' => 'array',
      'ids.*' => 'integer|exists:images,id',
      'tags' => 'array',
      'tags.*' => 'string',
      'name' => 'nullable|string',
      'description' => 'nullable|string',
      'attribution' => 'string',
      'status' => 'string',
    ]);

    $queryIds = $request->query('ids', []);

    $images = Image::whereIn('id', $validated['ids'])->get();

    foreach ($images as $image) {
      if (isset($validated['tags'])) {
        $tagIds = [];
        foreach ($validated['tags'] as $tagName) {
          $tag = Tag::firstOrCreate(['name' => $tagName]);
          $tagIds[] = $tag->id;
        }

        $image->tags()->syncWithoutDetaching($tagIds);
      }

      if (isset($validated['status'])) {
        $image->status = $validated['status'];
      }

      if (isset($validated['name'])) {
        $image->name = $validated['name'];
      }

      if (isset($validated['attribution'])) {
        $image->attribution = $validated['attribution'];
      }

      if (isset($validated['description'])) {
        $image->description = $validated['description'];
      }

      $image->save();
    }

    $updatedImages = Image::whereIn('id', $queryIds)->with('tags')->get();
    $tags = Tag::all();

    return back()->with([
      'images' => $updatedImages,
      'tags' => $tags,
    ]);
  }

  public function view($id)
  {
    $image = Image::with([
      'optimizedImages' => function ($query) {
        $query->whereIn('size', ['small', 'medium', 'large']);
      },
      'tags',
    ])->findOrFail($id);

    $similarImages = $image->getSimilarImages();

    return Inertia::render('Image/View', [
      'image' => $image,
      'similarImages' => $similarImages,
    ]);
  }

  public function edit($id)
  {
    $image = Image::findOrFail($id);
    return Inertia::render('Image/Edit', ['image' => $image]);
  }

  public function manageImage(int $id)
  {
    $image = Image::with([
      'optimizedImages' => function ($query) {
        $query->whereIn('size', ['small', 'medium', 'large']);
      },
      'tags',
    ])->findOrFail($id);
    $tags = Tag::all();

    return Inertia::render('Manage/Image', [
      'image' => $image,
      'tags' => $tags,
    ]);
  }

  public function manage(Request $request)
  {
    $query = $request->input('query');
    $count = $request->input('count', 25);
    $user = Auth::user();

    if ($user) {
      if ($query) {
        $imageIds = Image::search($query)
          ->where('user_id', $user->id)
          ->get()
          ->pluck('id');

        $images = Image::whereIn('id', $imageIds)
          ->with('tags')
          ->paginate($count);
      } else {
        $images = Image::where('user_id', $user->id)
          ->with('tags')
          ->paginate($count);
      }

      if ($request->wantsJson()) {
        return response()->json($images);
      }

      return Inertia::render('Manage/Images', ['images' => $images]);
    }

    return redirect()->route('login');
  }

  public function delete($id)
  {
    $image = Image::where('id', $id)
      ->where('user_id', Auth::id())
      ->firstOrFail();

    $image->delete();
    return redirect()->route('images.manage');
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

    return back();
  }

  public function adminShow(int $id)
  {
    $image = Image::findOrFail($id);
    $restrictions = Restriction::all();
    $tags = Tag::all();

    return Inertia::render('Admin/Image', [
      'image' => $image,
      'restrictions' => $restrictions,
      'tags' => $tags,
    ]);
  }

  public function adminManageImages(Request $request)
  {
    $query = $request->input('query');
    $count = $request->input('count', 25);

    if ($query) {
      $imageIds = Image::search($query)->get()->pluck('id');
      $images = Image::whereIn('id', $imageIds)->with('tags')->paginate($count);
    } else {
      $images = Image::with('tags')->paginate($count);
    }

    if ($request->wantsJson()) {
      return response()->json($images);
    }

    return Inertia::render('Admin/Manage', ['images' => $images]);
  }

  public function adminRestrictImage(int $id, Request $request)
  {
    $validated = $request->validate([
      'restriction_ids' => 'required|array',
      'restriction_ids.*' => 'int|exists:restrictions,id',
    ]);
    $image = Image::findOrFail($id);

    $image->restrict($validated['restriction_ids']);

    return back();
  }

  public function adminLiftImageRestriction(int $id, Request $request)
  {
    $valid = $request->validate([
      'restriction_ids' => 'required|Array',
      'restriction_ids.*' => 'int|exists:restrictions,id',
    ]);
    $image = Image::findOrFail($id);

    $image->liftRestriction($valid['restriction_ids']);

    return back();
  }

  public function adminDelete(int $id)
  {
    Image::destroy($id);

    return redirect()->route('admin.images.index');
  }

  public function adminBulkDelete(Request $request)
  {
    $validated = $request->validate([
      'ids' => 'required|array|min:1',
      'ids.*' => 'integer|exists:images,id',
    ]);

    Image::destroy($validated['ids']);

    return back();
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
