<?php

namespace App\Services;

use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;

class AzureBlobStorageAdapter implements FilesystemContract
{
  protected $client;
  protected $container;

  public function __construct(BlobRestProxy $client, $container)
  {
    $this->client = $client;
    $this->container = $container;
  }

  public function put($path, $contents, array $options = [])
  {
    $this->client->createBlockBlob($this->container, $path, $contents);
    return true;
  }

  public function get($path)
  {
    try {
      $blob = $this->client->getBlob($this->container, $path);
      return stream_get_contents($blob->getContentStream());
    } catch (\Exception $e) {
      throw new FileNotFoundException($path);
    }
  }

  public function delete($path)
  {
    $this->client->deleteBlob($this->container, $path);
    return true;
  }

  public function exists($path)
  {
    try {
      $this->client->getBlobProperties($this->container, $path);
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  public function url($path)
  {
    $accountName = env('AZURE_STORAGE_ACCOUNT_NAME');
    return "https://{$accountName}.blob.core.windows.net/{$this->container}/{$path}";
  }

  public function readStream($path)
  {
    $blob = $this->client->getBlob($this->container, $path);
    return $blob->getContentStream();
  }

  public function writeStream($path, $resource, array $options = [])
  {
    $contents = stream_get_contents($resource);
    return $this->put($path, $contents, $options);
  }

  // Implement other necessary methods if needed...
}
