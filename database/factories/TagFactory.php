<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
  protected $model = Tag::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'name' => $this->faker->unique()->word,
    ];
  }

  /**
   * Custom state to use predefined tags.
   *
   * @return Factory
   */
  public function customTag($name)
  {
    return $this->state(function (array $attributes) use ($name) {
      if (Tag::where('name', $name)->exists()) {
        return [];
      }
      return [
        'name' => $name,
      ];
    });
  }
}
