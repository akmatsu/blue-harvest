<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->input('query');
    $limit = $request->input('count', 25);

    if ($query) {
      $users = User::search($query)->paginate($limit);
    } else {
      $users = User::paginate($limit);
    }

    return Inertia::render('Admin/Users', ['users' => $users]);
  }

  public function view(string $id)
  {
    $user = User::findOrFail($id);
    return Inertia::render('Admin/User', ['user' => $user]);
  }

  public function update()
  {
    return back();
  }

  public function create()
  {
    return redirect()->route('admin.users.view', ['id' => 1]);
  }

  public function delete()
  {
    return redirect()->route('admin.users');
  }

  public function deleteBulk(Request $request)
  {
    $validated = $request->validate([
      'ids' => 'required|array|min:1',
      'ids.*' => 'integer|exists:users,id',
    ]);

    User::destroy($validated['ids']);

    return back();
  }
}
