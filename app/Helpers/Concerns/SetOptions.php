<?php

namespace App\Helpers\Concerns;

use App\Helpers\SmartCrop;
use Illuminate\Support\Str;

trait SetOptions
{
  protected int $width = 0;
  protected int $height = 0;
  protected string $crop = 'none';
  protected string $extension = 'webp';
  protected string $quality = '75';
  protected ?string $filter = null;

  public static function input($input): static
  {
    return new static($input);
  }

  public function options(object $opts)
  {
    $this->width = $opts->width;
    $this->height = $opts->height;
    $this->crop = $opts->crop;
    $this->extension = $opts->extension;
    $this->quality = $opts->quality;
    $this->filter = $opts->filter;

    return $this;
  }
}
