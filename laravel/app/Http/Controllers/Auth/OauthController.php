<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class OauthController extends Controller
{
  public function redirectToMicrosoft()
  {
    return Socialite::driver('microsoft')->redirect();
  }

  public function handleMicrosoftCallback()
  {
    $user = Socialite::driver('microsoft')->user();

    // Find or create a user in your database
    $authUser = User::updateOrCreate(
      ['email' => $user->getEmail()],
      ['name' => $user->getName()]
    );

    Auth::login($authUser, true);

    return redirect()->intended('index');
  }
}
