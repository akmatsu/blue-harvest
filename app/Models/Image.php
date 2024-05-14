<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'name',
    'path',
    'mime_type',
    'size',
    'url',
    'width',
    'height',
    'folder_name',
  ];

  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }

  protected static function boot()
  {
    parent::boot();

    static::deleting(function ($image) {
      $image->deleteFiles();
    });
  }

  public function deleteFiles()
  {
    if (Storage::exists($this->path)) {
      Storage::delete($this->path);
    }

    foreach ($this->optimizedImages as $optimizedImage) {
      if (Storage::exists($optimizedImage->path)) {
        Storage::delete($optimizedImage->path);
      }
    }
  }

  public function optimizedImages()
  {
    return $this->hasMany(OptimizedImage::class);
  }

  public function predefinedImages()
  {
    $optimizedImages = $this->optimizedImages;
    $grouped = [
      'small' => $optimizedImages->where('size', 'small')->first(),
      'medium' => $optimizedImages->where('size', 'medium')->first(),
      'large' => $optimizedImages->where('size', 'large')->first(),
    ];

    return $grouped;
  }

  public function tags()
  {
    return $this->belongsToMany(Tag::class);
  }
}
