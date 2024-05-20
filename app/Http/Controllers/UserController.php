<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
  public function index()
  {
    $users = User::all();
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

  public function deleteBulk()
  {
    return back();
  }
}
