<?php

namespace App\Http\Controllers;

use App\Models\Flag;
use App\Models\Restriction;
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
    $restrictions = Restriction::all();
    return Inertia::render('Admin/Flag', [
      'flag' => $flag,
      'restrictions' => $restrictions,
    ]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'flaggable_id' => 'required|int',
      'flaggable_type' => 'required|string',
      'reason' => 'required|string',
    ]);

    $userId = auth()->check() ? auth()->id() : null;

    $flag = Flag::create([
      'user_id' => $userId,
      'flaggable_id' => $request->flaggable_id,
      'flaggable_type' => $request->flaggable_type,
      'reason' => $request->reason,
    ]);

    $flag->flaggable->pendingReview();

    return back()->with('message', 'Thank you for your report.');
  }

  public function dismiss(int $id)
  {
    $flag = Flag::findOrFail($id);
    $flaggable = $flag->flaggable;
    if ($flaggable) {
      $flaggable->publish();
    }
    $flag->delete();
    return redirect()
      ->route('admin.flags.index')
      ->with('message', 'Flag Dismissed.');
  }

  public function dismissBulk(Request $request)
  {
    $valid = $request->validate([
      'flag_ids' => 'required|array',
      'flag_ids.*' => 'int|exists:flags,id',
    ]);

    $flags = Flag::find($valid['flag_ids']);
    $flags->map(function ($flag) {
      $flaggable = $flag->flaggable;
      if ($flaggable) {
        $flaggable->publish();
      }
    });

    Flag::destroy($valid['flag_ids']);

    return back()->with('message', 'Flags dismissed.');
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

  public function deleteFlaggableBulk(Request $request)
  {
    $valid = $request->validate([
      'flag_ids' => 'required|array',
      'flag_ids.*' => 'int|exists:flags,id',
    ]);

    $flags = Flag::find($valid['flag_ids']);
    $flags->map(function ($flag) {
      return $flag->flaggable->delete();
    });

    return back()->with('message', 'Flagged items deleted.');
  }

  public function restrictFlaggable(int $id, Request $request)
  {
    $valid = $request->validate([
      'restriction_ids' => 'required|array',
      'restriction_ids.*' => 'int|exists:restrictions,id',
    ]);
    $flag = Flag::findOrFail($id);
    $flaggable = $flag->flaggable;

    if ($flaggable) {
      $flaggable->restrict($valid['restriction_ids']);
      $flaggable->publish();
    }

    return redirect()
      ->route('admin.flags.index')
      ->with('message', 'Flagged item has been restricted');
  }

  public function liftFlaggableRestriction(int $id, Request $request)
  {
    $valid = $request->validate([
      'restriction_ids' => 'required|array',
      'restriction_ids.*' => 'int|exists:restrictions,id',
    ]);
    $flag = Flag::findOrFail($id);
    $flaggable = $flag->flaggable;

    if ($flaggable) {
      $flaggable->liftRestriction($valid['restriction_ids']);
    }

    return redirect()
      ->route('admin.flags.index')
      ->with('message', 'Successfully lifted restriction.');
  }
}
