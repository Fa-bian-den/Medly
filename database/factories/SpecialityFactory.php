<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Speciality;

class SpecialityFactory extends Factory
{
    protected $model = Speciality::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->jobTitle(),
            'code' => strtoupper($this->faker->lexify('???')),
            'description' => $this->faker->sentence(),
        ];
    }
}