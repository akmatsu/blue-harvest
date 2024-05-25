<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restriction extends Model
{
  use HasFactory;

  protected $fillable = ['name', 'description'];

  public function images()
  {
    return $this->belongsToMany(Image::class);
  }
}
