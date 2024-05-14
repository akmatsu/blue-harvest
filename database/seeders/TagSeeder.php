<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $tags = [
      // Nature tags
      [
        'name' => 'Wildlife',
        'description' => 'Images of animals in their natural habitats',
      ],
      ['name' => 'Mountains', 'description' => 'Mountainous landscapes'],
      ['name' => 'Rivers', 'description' => 'Flowing rivers and streams'],
      ['name' => 'Lakes', 'description' => 'Lakes and other bodies of water'],
      ['name' => 'Forests', 'description' => 'Forested areas'],
      ['name' => 'Glaciers', 'description' => 'Glacial landscapes'],
      ['name' => 'Flora', 'description' => 'Plant life'],
      ['name' => 'Fauna', 'description' => 'Animal life'],
      ['name' => 'Sunrise', 'description' => 'Sunrise scenes'],
      ['name' => 'Sunset', 'description' => 'Sunset scenes'],
      ['name' => 'Northern Lights', 'description' => 'Aurora Borealis'],
      ['name' => 'Landscapes', 'description' => 'General landscape images'],
      ['name' => 'Hiking Trails', 'description' => 'Images of hiking trails'],
      ['name' => 'Scenic Views', 'description' => 'Scenic vistas'],
      ['name' => 'Parks', 'description' => 'Public parks and recreation areas'],
      ['name' => 'Wetlands', 'description' => 'Wetland areas'],
      ['name' => 'Birdwatching', 'description' => 'Birdwatching scenes'],
      ['name' => 'Camping', 'description' => 'Camping sites and activities'],
      ['name' => 'Fishing', 'description' => 'Fishing activities'],
      ['name' => 'Wilderness', 'description' => 'Wilderness areas'],

      // Projects tags
      ['name' => 'Construction', 'description' => 'Construction projects'],
      ['name' => 'Infrastructure', 'description' => 'Infrastructure projects'],
      ['name' => 'Road Work', 'description' => 'Road construction and repair'],
      ['name' => 'Bridges', 'description' => 'Bridge projects'],
      [
        'name' => 'Public Buildings',
        'description' => 'Public building projects',
      ],
      ['name' => 'Schools', 'description' => 'School building and renovation'],
      [
        'name' => 'Parks Development',
        'description' => 'Park development projects',
      ],
      [
        'name' => 'Water Projects',
        'description' => 'Water infrastructure projects',
      ],
      [
        'name' => 'Renewable Energy',
        'description' => 'Renewable energy projects',
      ],
      [
        'name' => 'Community Centers',
        'description' => 'Community center projects',
      ],
      [
        'name' => 'Housing Projects',
        'description' => 'Housing development projects',
      ],
      [
        'name' => 'Environmental Projects',
        'description' => 'Environmental restoration projects',
      ],
      [
        'name' => 'Clean-Up Projects',
        'description' => 'Community clean-up projects',
      ],
      [
        'name' => 'Recreational Facilities',
        'description' => 'Recreational facility projects',
      ],
      [
        'name' => 'Urban Planning',
        'description' => 'Urban planning and development',
      ],
      ['name' => 'Transportation', 'description' => 'Transportation projects'],
      [
        'name' => 'Restoration Projects',
        'description' => 'Restoration and renovation projects',
      ],
      [
        'name' => 'Utility Work',
        'description' => 'Utility infrastructure projects',
      ],
      ['name' => 'Public Works', 'description' => 'Public works projects'],
      ['name' => 'Renovation', 'description' => 'Building renovation projects'],

      // Government Events tags
      [
        'name' => 'Town Hall Meetings',
        'description' => 'Town hall meetings and discussions',
      ],
      [
        'name' => 'Community Gatherings',
        'description' => 'Community gatherings and events',
      ],
      [
        'name' => 'Public Announcements',
        'description' => 'Public announcements and notices',
      ],
      [
        'name' => 'Official Ceremonies',
        'description' => 'Official government ceremonies',
      ],
      [
        'name' => 'Ribbon Cuttings',
        'description' => 'Ribbon cutting ceremonies',
      ],
      ['name' => 'Festivals', 'description' => 'Local festivals and events'],
      ['name' => 'Parades', 'description' => 'Community parades'],
      [
        'name' => 'Public Hearings',
        'description' => 'Public hearings and discussions',
      ],
      ['name' => 'Workshops', 'description' => 'Educational workshops'],
      [
        'name' => 'Educational Events',
        'description' => 'Community educational events',
      ],
      [
        'name' => 'Volunteer Events',
        'description' => 'Volunteer opportunities and events',
      ],
      [
        'name' => 'Cultural Events',
        'description' => 'Cultural celebrations and events',
      ],
      ['name' => 'Awards Ceremonies', 'description' => 'Award ceremonies'],
      ['name' => 'Council Meetings', 'description' => 'City council meetings'],
      ['name' => 'Elections', 'description' => 'Local election events'],
      [
        'name' => 'Charity Events',
        'description' => 'Charity fundraising events',
      ],
      ['name' => 'Safety Drills', 'description' => 'Community safety drills'],
      ['name' => 'Celebrations', 'description' => 'Community celebrations'],
      ['name' => 'Local Markets', 'description' => 'Local market events'],
      [
        'name' => 'Press Conferences',
        'description' => 'Government press conferences',
      ],
    ];

    foreach ($tags as $tag) {
      Tag::factory()
        ->customTag($tag['name'], $tag['description'])
        ->create();
    }
  }
}
