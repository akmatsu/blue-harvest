<?php
// app/Providers/AzureBlobStorageServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use Illuminate\Support\Facades\Storage;

class AzureBlobStorageServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    Storage::extend('azure', function ($app, $config) {
      $client = BlobRestProxy::createBlobService(
        sprintf(
          'DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s',
          $config['name'],
          $config['key']
        )
      );

      return new Filesystem(
        new AzureBlobStorageAdapter(
          $client,
          $config['container'],
          $config['prefix'] ?? null
        )
      );
    });
  }
}
