<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OptimizedImage extends Model
{
  use HasFactory;

  protected $fillable = [
    'image_id',
    'size',
    'file_size',
    'path',
    'url',
    'width',
    'height',
  ];

  protected static function boot()
  {
    parent::boot();

    static::deleting(function ($image) {
      $image->deleteFiles();
    });
  }

  public function image()
  {
    return $this->belongsTo(Image::class);
  }

  public function deleteFiles()
  {
    if (Storage::exists($this->path)) {
      Storage::delete($this->path);
    }
  }
}
