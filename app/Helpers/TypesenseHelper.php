<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Typesense\Client;

class TypesenseHelper
{
  protected Client $typesense;

  public function __construct()
  {
    $this->typesense = new Client([
      'api_key' => config('services.typesense.api_key'),
      'nodes' => config('services.typesense.nodes'),
      'connection_timeout_seconds' => 2,
    ]);
  }

  public function logSearchQuery(string $query)
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

  public function extractFilters(string $query)
  {
    $filters = [];
    preg_match_all('/(tag):(\w+|"[\w\s]+")/', $query, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
      $filters[] = preg_replace('/"/', '', $match[2]);
    }

    return $filters;
  }

  public function stripFiltersFromQuery(string $query)
  {
    return preg_replace('/(tag):(\w+|"[\w\s]+")/', '', $query);
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
