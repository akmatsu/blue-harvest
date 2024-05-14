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
      'description' => $this->faker->sentence,
    ];
  }

  /**
   * Custom state to use predefined tags.
   *
   * @return Factory
   */
  public function customTag($name, $description = null)
  {
    return $this->state(function (array $attributes) use ($name, $description) {
      return [
        'name' => $name,
        'description' => $description,
      ];
    });
  }
}
