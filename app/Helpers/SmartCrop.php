<?php

namespace App\Helpers;

use App\Helpers\Concerns\SetOptions;
use Illuminate\Process\Exceptions\ProcessFailedException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Image as Img;

/* 
  TODO Add support for more query parameters 
  Height
  Width
  Quality
  Format: jpeg, png, webp, gif? - default should be webp since it's most performant over web. We should specify this to users.
*/

// TODO: Implement smart cropping.

class SmartCrop
{
  use SetOptions;

  protected string $input;
  protected string $output;
  public Img $image;

  const MAX_SIZE_BYTES = 25000000; // 25 MB, potentially unnecessary if only local files are handled
  const MAX_HEIGHT = 500; // Reducing max size for practical use
  const MAX_WIDTH = 500; // Reducing max size for practical use

  public function __construct($input)
  {
    $this->input = $input;
    $this->output = $this->generateOutputPath();
  }

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
      return $this->download();
    } catch (\Exception $e) {
      Log::error($e->getMessage());
    }
  }

  protected function generateOutputPath(): string
  {
    return 'public/processed_images/' . md5($this->input) . '.webp';
  }

  protected function resizeImage(): void
  {
    // Resize image only if it exceeds the maximum dimensions
    if (
      $this->image->width() > self::MAX_WIDTH ||
      $this->image->height() > self::MAX_HEIGHT
    ) {
      $this->image->pad(self::MAX_WIDTH, self::MAX_HEIGHT);
    }
  }

  protected function formatImage(): void
  {
    //
    $this->image->encodeByExtension('webp');
  }

  protected function saveImage()
  {
    // Store the image in the local filesystem
    $path = Storage::path($this->output);
    $this->image->save($path);
  }

  protected function imageExists(): bool
  {
    return Storage::exists($this->output);
  }

  public function download()
  {
    return Storage::download($this->output);
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
}
