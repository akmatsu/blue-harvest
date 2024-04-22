<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Image as Img;

class SmartCrop
{
  protected string $input;
  protected string $output;
  public Img $image;

  const MAX_SIZE_BYTES = 30000000; // 30 MB, potentially unnecessary if only local files are handled
  const MAX_HEIGHT = 1000; // Reducing max size for practical use
  const MAX_WIDTH = 1000; // Reducing max size for practical use

  public function __construct($input)
  {
    $this->input = $input;
    $this->output = $this->generateOutputPath();
  }

  public function createAndStoreImage(): string
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
    return 'public/processed_images/' . md5($this->input) . '.jpg';
  }

  protected function resizeImage(): void
  {
    // Resize image only if it exceeds the maximum dimensions
    if (
      $this->image->width() > self::MAX_WIDTH ||
      $this->image->height() > self::MAX_HEIGHT
    ) {
      $this->image->resize(self::MAX_WIDTH, self::MAX_HEIGHT, function (
        $constraint
      ) {
        $constraint->aspectRatio();
        $constraint->upsize();
      });
    }
  }

  protected function saveImageLocally(): string
  {
    // Store the image in the local filesystem
    $path = Storage::disk('local')->path($this->output);
    Log::info($path);
    $this->image->toJpeg(90)->save($path);
    return $path;
  }
}
