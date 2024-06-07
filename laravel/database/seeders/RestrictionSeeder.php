<?php

namespace Database\Seeders;

use App\Models\Restriction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestrictionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $restrictions = [
      [
        'name' => 'Political',
        'description' =>
          'This image include public figures. Check with Public Affairs before using.',
      ],
      [
        'name' => 'People',
        'description' =>
          'This image includes people, confirm that you are not using without permission. Check with Public Affairs before using.',
      ],
      [
        'name' => 'Children',
        'description' =>
          'This image includes children, confirm that you are not using without permission. Check with Public Affairs before using.',
      ],
      [
        'name' => 'OSHA',
        'description' =>
          'This image includes heavy equipment or people working. Check with originating department and Health & Safety Manager before using.',
      ],
      [
        'name' => 'Emergency',
        'description' =>
          'This image includes depictions of Emergency Situations, confirm with Emergency Management Division before using.',
      ],
    ];

    foreach ($restrictions as $r) {
      Restriction::factory()
        ->customRestriction($r['name'], $r['description'])
        ->create();
    }
  }
}
