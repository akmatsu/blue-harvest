<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/azure/redirect', function () {
  return Socialite::driver('azure')->redirect();
})->name('login.azure');

Route::get('/azure/callback', function () {
  $azureUser = Socialite::driver('azure')->user();

  $user = User::where('email', $azureUser->email)
    ->orWhere('azure_id', $azureUser->id)
    ->first();

  if ($user) {
    $user->update([
      'name' => $azureUser->name,
      'email' => $azureUser->email,
      'azure_id' => $azureUser->id,
    ]);
  } else {
    $user = User::create([
      'name' => $azureUser->name,
      'email' => $azureUser->email,
      'azure_id' => $azureUser->id,
      'password' => bcrypt(bin2hex(random_bytes(16))),
    ]);
  }

  Auth::login($user);

  return redirect('/');
})->name('login.azure.callback');

Route::middleware('auth')->group(function () {
  Route::post('logout', [
    AuthenticatedSessionController::class,
    'destroy',
  ])->name('logout');
});
