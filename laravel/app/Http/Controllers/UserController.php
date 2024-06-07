<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

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
    $roles = Role::all();
    return Inertia::render('Admin/User', ['user' => $user, 'roles' => $roles]);
  }

  public function update(int $id, Request $request)
  {
    $validated = $request->validate([
      'name' => 'nullable|string|min:3',
      'email' => 'nullable|email',
      'password' => ['nullable', Password::default(), 'confirmed'],
      'password_confirmation' => 'required_with:password|same:password',
      'roles' => 'nullable|array',
      'roles.*' => 'integer|exists:roles,id',
    ]);

    $user = User::findOrFail($id);

    $user->fill(array_filter($validated, fn($value) => $value !== null));

    if (isset($validated['password'])) {
      $user->password = Hash::make($validated['password']);
    }

    $user->save();

    if (isset($validated['roles'])) {
      $user->roles()->sync($validated['roles']);
    }

    return back();
  }

  public function create(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|min:3',
      'email' => 'required|email',
      'password' => ['required', Password::default(), 'confirmed'],
      'password_confirmation' => 'same:password',
    ]);

    $user = new User();
    $user->fill($validated);
    $user->password = Hash::make($validated['password']);
    $user->save();

    return redirect()->route('admin.users.view', ['id' => $user->id]);
  }

  public function delete($id)
  {
    User::destroy($id);
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
