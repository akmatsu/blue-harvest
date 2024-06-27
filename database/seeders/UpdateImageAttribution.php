<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Seeder;

class UpdateImageAttribution extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Image::chunkById(100, function ($images) {
      foreach ($images as $image) {
        $user = User::find($image->user_id);
        if ($user) {
          Image::where('id', $image->id)->update([
            'attribution' => 'MSB Public Affairs',
          ]);
        }
      }
    });
  }
}
