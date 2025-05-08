<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\House>
 */
class HouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'house_number' => $this->faker->unique()->word(),
            'status'       => $this->faker->randomElement(['Occupied', 'Vacant']),
            'created_at'   => now(),
            'updated_at'   => now(),
        ];
    }
}
