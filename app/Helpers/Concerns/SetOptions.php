<?php

namespace App\Helpers\Concerns;

use App\Helpers\SmartCrop;
use Illuminate\Support\Str;

trait SetOptions
{
  public static function options(object $options): static
  {
    [$width, $height] = getimagesize($options->input);
    if ($options->width && !$options->height) {
      $ratio = $options->width / $width;
      $options->height = round($height * $ratio);
    } elseif (!$options->width && $options->height) {
      $ratio = $options->height / $height;
      $options->width = round($width * $ratio);
    } elseif (!$options->width && !$options->height) {
      $options->width = $width;
      $options->height = $height;
    }

    return self::url($options->input)
  }
}
