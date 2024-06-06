<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Restriction;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Intervention\Image\Laravel\Facades\Image as ImageFacade;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Typesense\Client;

class ImageController extends Controller
{
  protected Client $typesense;

  public function __construct()
  {
    $this->typesense = new Client([
      'api_key' => env('TYPESENSE_API_KEY'),
      'nodes' => [
        [
          'host' => env('TYPESENSE_HOST', 'localhost'),
          'port' => env('TYPESENSE_PORT', '8108'),
          'protocol' => env('TYPESENSE_PROTOCOL', 'http'),
        ],
      ],
      'connection_timeout_seconds' => 2,
    ]);
  }
  public function index(Request $request)
  {
    $query = $request->input('query');
    $limit = $request->input('count', 25);
    $imagesQuery = Image::query();

    if ($query) {
      $imageIds = Image::search($query)->get()->pluck('id');
      $imagesQuery->whereIn('id', $imageIds);
    }

    if (!Auth::check()) {
      $imagesQuery->where('is_restricted', false);
    }

    $images = $imagesQuery
      ->with([
        'optimizedImages' => function ($query) {
          $query->whereIn('size', ['small', 'medium', 'large']);
        },
      ])
      ->paginate($limit);

    if ($query && $images->total() > 0) {
      $this->logSearchQuery($query);
    }

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
      $dbImage->save();
      $this->generateOptimizedImages($dbImage, $path, $uniqueFolder);

      $this->autoTag($dbImage, $file);

      $ids[] = $dbImage->id;
    }

    return redirect()->route('upload.results', ['ids' => $ids]);
  }

  public function updateImage(int $id, Request $request)
  {
    $request->validate([
      'tags' => 'array',
      'tags.*' => 'string',
      'name' => 'string',
    ]);

    $image = Image::findOrFail($id);

    if ($request->has('name')) {
      $image->name = $request->input('name');
      $image->save();
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

  public function view($id)
  {
    $image = Image::with([
      'optimizedImages' => function ($query) {
        $query->whereIn('size', ['small', 'medium', 'large']);
      },
      'tags',
    ])->findOrFail($id);

    $tagIds = $image->tags->pluck('id');

    $similarImages = Image::whereHas('tags', function ($query) use ($tagIds) {
      $query->whereIn('tags.id', $tagIds);
    })
      ->where('id', '!=', $id)
      ->take(15)
      ->get();

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
    $image = Image::findOrFail($id);
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

        if ($images->total() > 0) {
          $this->logSearchQuery($query);
        }
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
      if ($images->total() > 0) {
        $this->logSearchQuery($query);
      }
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

  private function autoTag($dbImage, $file)
  {
    try {
      $contents = file_get_contents($file->getRealPath());

      $res = Http::attach('file', $contents, $file->getClientOriginalName())
        ->timeout(60)
        ->post('http://localhost:9001');

      $resJson = $res->json();
      $resTags = $resJson['tags'];
      $resFlag = $resJson['flag'];
      Log::info($resJson);

      $tagIds = [];
      foreach ($resTags as $tagName) {
        $tag = Tag::firstOrCreate(['name' => $tagName]);
        $tagIds[] = $tag->id;
      }

      $dbImage->tags()->syncWithoutDetaching($tagIds);
    } catch (ProcessFailedException $exception) {
      Log::error('Process failed', [
        'message' => $exception->getMessage(),
        'output' => $exception->getProcess()->getErrorOutput(),
      ]);
      throw $exception;
    } catch (\Exception $e) {
      Log::error('An unexpected error occurred', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ]);
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

  private function logSearchQuery(string $query)
  {
    try {
      $this->createPopularSearchesCollectionIfNotExists();

      // Search for the document with the given query
      $searchResults = $this->typesense->collections[
        'popular_searches'
      ]->documents->search([
        'q' => $query,
        'query_by' => 'query',
        'filter_by' => 'query:=' . $query,
        'per_page' => 1,
      ]);

      if (count($searchResults['hits']) > 0) {
        // If the document exists, increment the count
        $document = $searchResults['hits'][0]['document'];
        $updatedCount = $document['count'] + 1;
        $this->typesense->collections['popular_searches']->documents[
          $document['id']
        ]->update([
          'query' => $query,
          'count' => $updatedCount,
          'timestamp' => now()->timestamp,
        ]);
      } else {
        // If the document does not exist, create a new one
        $this->typesense->collections['popular_searches']->documents->upsert([
          'query' => $query,
          'count' => 1,
          'timestamp' => now()->timestamp,
        ]);
      }
    } catch (\Exception $e) {
      Log::error('Failed to log search query: ' . $e->getMessage());
    }
  }

  private function createPopularSearchesCollectionIfNotExists()
  {
    try {
      $this->typesense->collections['popular_searches']->retrieve();
    } catch (\Typesense\Exceptions\ObjectNotFound $e) {
      $this->typesense->collections->create([
        'name' => 'popular_searches',
        'fields' => [
          ['name' => 'query', 'type' => 'string'],
          ['name' => 'count', 'type' => 'int32'],
          ['name' => 'timestamp', 'type' => 'int32'],
        ],
      ]);
    }
  }
}
