<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Center;
use App\Models\Municipality;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Center>
 */
class CenterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Hospital',
            'address' => $this->faker->address,
            'municipality_id' => Municipality::inRandomOrder()->first()?->id ?? Municipality::factory(),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'phone' => $this->faker->phoneNumber,

        ];
    }
}
