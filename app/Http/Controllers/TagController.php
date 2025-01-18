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
    $validated = $request->validate([
      'name' => 'required|string|unique:tags,name',
    ]);

    Tag::create($validated);

    return back();
  }
}
