<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restriction>
 */
class RestrictionFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'name' => $this->faker->unique()->words(),
      'description' => $this->faker->sentence(),
    ];
  }

  public function customRestriction(
    $name,
    $description = 'This is restricted, check with an Admin before using'
  ) {
    return $this->state(function (array $attributes) use ($name, $description) {
      return [
        'name' => $name,
        'description' => $description,
      ];
    });
  }
}
