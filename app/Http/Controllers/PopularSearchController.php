<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Typesense\Client;

class PopularSearchController extends Controller
{
  protected $typesense;

  public function __construct()
  {
    $this->typesense = new Client([
      'api_key' => config('services.typesense.api_key'),
      'nodes' => config('services.typesense.nodes'),
      'connection_timeout_seconds' => 2,
    ]);
  }

  public function getPopularSearches(Request $request)
  {
    $userQuery = $request->input('query', '*');

    try {
      $response = $this->typesense->collections[
        'popular_searches'
      ]->documents->search([
        'q' => $userQuery,
        'query_by' => 'query',
        'sort_by' => 'count:desc',
        'per_page' => 10,
      ]);
      return response()->json($response['hits']);
    } catch (\Exception $e) {
      Log::error('Failed to fetch popular searches: ' . $e->getMessage());
      return response()->json(
        [
          'error' => 'Failed to fetch popular searches',
          'message' => $e->getMessage(),
        ],
        500
      );
    }
  }
}
