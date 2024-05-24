<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flag extends Model
{
  protected $fillable = ['user_id', 'flaggable_id', 'flaggable_type', 'reason'];
  protected $with = ['flaggable'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function flaggable()
  {
    return $this->morphTo();
  }
}
