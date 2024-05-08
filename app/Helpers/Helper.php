<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Interfaces\ImageInterface;

function storeInterventionImage(string $filePath, ImageInterface $image)
{
  if (ensureDirectoryExists($filePath)) {
    $storePath = Storage::path($filePath);
    $image->save($storePath);
    return $storePath;
  } else {
    throw new Error('Failed to save file to ' . $filePath);
  }
}

function ensureDirectoryExists(string $filePath)
{
  $dir = dirname($filePath);
  if (!Storage::exists($dir)) {
    if (!Storage::makeDirectory($dir)) {
      return false;
    }
  }
  return true;
}

function generateOptimizedImagePath(UploadedFile $file, int $width, int $height)
{
  return 'public/processed_images/' .
    $width .
    'x' .
    $height .
    '/' .
    $file->hashName();
}
