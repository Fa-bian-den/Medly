<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'birthdate' => $this->faker->date(),
        'address' => $this->faker->address(),
        'phone' => $this->faker->phoneNumber(),
        'gender' => $this->faker->randomElement(['male','female']),
        'documents_metadata' => null,
        'professional_details' => null,
    ];

    }
}
