<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
  use HasFactory, Notifiable, HasApiTokens, HasRoles, Searchable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'azure_id',
    'azure_token',
    'azure_refresh_token',
  ];

  protected $with = ['roles'];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = ['password', 'remember_token'];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  public function images()
  {
    return $this->hasMany('App\Models\Image');
  }

  public function toSearchableArray()
  {
    return array_merge($this->toArray(), [
      'id' => (string) $this->id,
      'created_at' => $this->created_at->timestamp,
      'roles' => $this->roles->pluck('name')->toArray(),
    ]);
  }
}
