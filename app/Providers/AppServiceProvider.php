<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    if (env('APP_ENV') === 'production') {
      URL::forceScheme('https');
    }

    Event::listen(function (
      \SocialiteProviders\Manager\SocialiteWasCalled $event
    ) {
      $event->extendSocialite(
        'microsoft',
        \SocialiteProviders\Microsoft\Provider::class
      );
    });
  }
}
