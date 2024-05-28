<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Image extends Model
{
  use HasFactory, Searchable;

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
    'is_restricted',
  ];

  protected $with = ['tags', 'restrictions'];

  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }

  protected static function boot()
  {
    parent::boot();

    static::deleting(function ($image) {
      $image->deleteFiles();
      $image->deleteFlags();
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

  public function deleteFlags()
  {
    $this->flags()->each(function ($flag) {
      $flag->delete();
    });
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

  public function restrictions()
  {
    return $this->belongsToMany(Restriction::class);
  }

  public function toSearchableArray()
  {
    return array_merge($this->toArray(), [
      'id' => (string) $this->id,
      'name' => (string) $this->name,
      'created_at' => $this->created_at->timestamp,
      'tags' => $this->tags->pluck('name')->toArray(),
      'tag_descriptions' => $this->tags->pluck('description')->toArray(),
    ]);
  }

  public function flags(): MorphMany
  {
    return $this->morphMany(Flag::class, 'flaggable');
  }

  public function restrict(array $restrictionIds)
  {
    $this->restrictions()->sync($restrictionIds);
    $this->update([
      'is_restricted' => true,
    ]);
    $this->deleteFlags();
  }

  public function liftRestriction(array $restrictionIds)
  {
    $this->restrictions()->sync($restrictionIds);
    if ($this->restrictions()->count() == 0) {
      $this->update([
        'is_restricted' => false,
      ]);
    }
  }
}
