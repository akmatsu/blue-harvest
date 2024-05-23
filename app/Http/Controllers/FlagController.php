<?php

namespace App\Http\Controllers;

use App\Models\Flag;
use Illuminate\Http\Request;

class FlagController extends Controller
{
  public function store(Request $request)
  {
    $request->validate([
      'flaggable_id' => 'required|int',
      'flaggable_type' => 'required|string',
      'reason' => 'required|string',
    ]);
    $userId = auth()->check() ? auth()->id() : null;

    Flag::create([
      'user_id' => $userId,
      'flaggable_id' => $request->flaggable_id,
      'flaggable_type' => $request->flaggable_type,
      'reason' => $request->reason,
    ]);

    return back()->with('message', 'Thank you for your report.');
  }
}
