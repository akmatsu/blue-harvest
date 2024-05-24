<?php

namespace App\Http\Controllers;

use App\Models\Flag;
use App\Models\Image;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FlagController extends Controller
{
  public function index(Request $request)
  {
    $count = $request->input('count', 25);
    $flags = Flag::paginate($count);
    return Inertia::render('Admin/Flags', ['flags' => $flags]);
  }

  public function show(int $id)
  {
    $flag = Flag::findOrFail($id);
    return Inertia::render('Admin/Flag', ['flag' => $flag]);
  }

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

  public function dismiss(int $id)
  {
    $flag = Flag::findOrFail($id);
    $flag->delete();
    return redirect()
      ->route('admin.flags.index')
      ->with('message', 'Flag Dismissed.');
  }

  public function deleteFlaggable(int $id)
  {
    $flag = Flag::findOrFail($id);
    $flaggable = $flag->flaggable;
    $flaggable->delete();

    return redirect()
      ->route('admin.flags.index')
      ->with('message', 'Flagged item deleted.');
  }

  public function restrictFlaggable(int $id, Request $request)
  {
    $validated = $request->validate([
      'restriction_reason' => 'required|string|max:255',
    ]);
    $flag = Flag::findOrFail($id);
    $flaggable = $flag->flaggable;

    if ($flaggable) {
      $flaggable->restrict($validated['restriction_reason']);
    }

    return redirect()
      ->route('admin.flags.index')
      ->with('message', 'Flagged item has been restricted');
  }

  public function liftFlaggableRestriction(int $id)
  {
    $flag = Flag::findOrFail($id);
    $flaggable = $flag->flaggable;

    if ($flaggable) {
      $flaggable->liftRestriction();
    }

    return redirect()
      ->route('admin.flags.index')
      ->with('message', 'Successfully lifted restriction.');
  }
}
