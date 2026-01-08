<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\OauthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
