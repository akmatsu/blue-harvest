<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Interfaces\ImageInterface;
use Illuminate\Support\Str;

function storeOptimizedImage(string $filePath, ImageInterface $image)
{
  if (ensureDirectoryExists($filePath)) {
    $encodedImage = $image->encodeByExtension(extension: 'webp', quality: 75);
    $path =
      $filePath .
      $image->width() .
      'x' .
      $image->height() .
      '_optimized_image.webp';
    Storage::put($path, $encodedImage->toFilePointer());
    return $path;
  } else {
    throw new Error('Failed to save file to ' . $filePath);
  }
}

function storeBaseImage(string $path, UploadedFile $file)
{
  // return Storage::disk('azure')->write($path, $file);
  if (ensureDirectoryExists($path)) {
    return Storage::putFileAs($path, $file, $file->getClientOriginalName());
  } else {
    throw new Error('Failed to save file to ' . $path);
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

function generateUniqueFolder()
{
  return 'public/uploads/' . Str::uuid();
}

function generateOptimizedImagePath(UploadedFile $file, int $width, int $height)
{
  return 'public/uploads/' .
    $file->hashName() .
    '/optimized_images/' .
    $width .
    'x' .
    $height .
    '_' .
    $file->getClientOriginalName();
}
