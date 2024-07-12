<?php

namespace App\Http\Controllers;

use App\Helpers\TypesenseHelper;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RESTImageController extends Controller
{
  protected TypesenseHelper $tsHelper;

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

    if ($query && $results->total() > 0) {
      $this->tsHelper->logSearchQuery($query);
    }

    return response()->json($results);
  }
}
