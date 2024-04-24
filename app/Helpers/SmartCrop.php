<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
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

// TODO: Prevent duplication. If an image that fits the parameters has already been set don't generate a new one

// TODO: Implement smart cropping.

class SmartCrop
{
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
    try {
      $this->image = Image::read(file_get_contents($this->input));
      $this->resizeImage();
      return $this->saveImageLocally();
    } catch (\Exception $e) {
      echo 'Caught exception: ', $e->getMessage(), '\n';
      return 'Oops';
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

  protected function saveImageLocally()
  {
    // Store the image in the local filesystem
    $path = Storage::path($this->output);
    Log::info($path);
    $this->image->toWebp(90)->save($path);
    return Storage::download($this->output);
  }
}
