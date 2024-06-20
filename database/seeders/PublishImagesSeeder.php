<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;

class PublishImagesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Image::chunkById(100, function ($images) {
      foreach ($images as $image) {
        Image::where('id', $image->id)->update([
          'is_published' => true,
        ]);
      }
    });
  }
}
