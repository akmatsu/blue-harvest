<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;

class TagController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->query('query');
    $tags = Tag::all();

    if ($query) {
      $tags = Tag::where('name', 'like', "%$query%")->get();
    }

    return response()->json($tags);
  }

  public function store(Request $request)
  {
    try {
      $validated = $request->validate([
        'name' => 'required|string|unique:tags,name',
      ]);

      $tag = Tag::create($validated);
      return response()->json($tag, 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json(
        [
          'message' => 'Validation failed',
          'errors' => $e->errors(),
        ],
        422
      );
    } catch (Exception $e) {
      return response()->json($e->getMessage(), $e->getCode());
    }
  }
}
