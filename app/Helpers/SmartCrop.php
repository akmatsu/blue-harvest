<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Bus;
use App\Jobs\DeleteFileJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Image as Img;

class SmartCrop
{
  protected string $input;
  protected string $output;
  public Img $image;
  protected int $width = 0;
  protected int $height = 0;
  protected string $crop = 'none';
  protected string $extension = 'webp';
  protected int $quality = 75;
  protected ?string $filter = null;

  const MAX_SIZE_BYTES = 25000000; // 25 MB, potentially unnecessary if only local files are handled
  const MAX_HEIGHT = 3840; // Reducing max size for practical use
  const MAX_WIDTH = 3840; // Reducing max size for practical use

  public function __construct($input, $opts)
  {
    $this->input = $input;
    $this->options($opts);
  }
  // route
  public function createAndStoreImage()
  {
    // if ($this->imageExists()) {
    //   return $this->download();
    // }

    try {
      $this->image = Image::read(Storage::get($this->input));
      if ($this->crop == 'smart') {
        $this->runSmartCropJs();
      }
      $this->resizeImage();
      $this->formatImage();
      $this->saveImage();
      $response = $this->download();

      // Bus::chain([new DeleteFileJob($this->output)])->dispatch();
      DeleteFileJob::dispatch($this->output);

      return $response;
    } catch (\Exception $e) {
      Log::error($e->getMessage());
    }
  }

  protected function generateOutputPath(): string
  {
    return 'public/processed_images/' .
      md5($this->input) .
      '.' .
      $this->extension;
  }

  protected function resizeImage(): void
  {
    if ($this->crop == 'none') {
      if ($this->height) {
        $this->image->resize(height: $this->height);
      }

      if ($this->width) {
        $this->image->resize(width: $this->width);
      }
    }

    if ($this->crop != 'smart' || $this->crop != 'none') {
      $this->image->crop($this->width, $this->height, position: $this->crop);
    }

    if (
      $this->image->width() > self::MAX_WIDTH ||
      $this->image->height() > self::MAX_HEIGHT
    ) {
      $this->image->pad(self::MAX_WIDTH, self::MAX_HEIGHT);
    }
  }

  protected function formatImage(): void
  {
    $this->image->encodeByExtension($this->extension);
  }

  protected function saveImage()
  {
    // Store the image in the local filesystem
    $path = Storage::path($this->output);
    if ($this->extension != 'png') {
      $this->image->save($path, quality: $this->quality);
    } else {
      $this->image->save($path);
    }
  }

  protected function imageExists(): bool
  {
    return Storage::exists($this->output);
  }

  public function download()
  {
    return Storage::response($this->output);
  }

  protected function runSmartCropJs()
  {
    $result = Process::path(base_path())->run('smartcrop');
    if ($result->failed()) {
      Log::error('Error: ' . $result->errorOutput());
    } else {
      Log::debug('Success: ' . $result->output());
    }
  }

  protected function options(object $opts)
  {
    $this->width = $opts->width;
    $this->height = $opts->height;
    $this->crop = $opts->crop;
    $this->extension = $opts->extension;
    $this->quality = $opts->quality;
    $this->filter = $opts->filter;
    $this->output = $this->generateOutputPath();

    return $this;
  }
}
