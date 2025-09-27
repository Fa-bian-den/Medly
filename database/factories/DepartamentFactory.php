<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Departament;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Departament>
 */
class DepartamentFactory extends Factory
{
    protected $model = Departament::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->state(),
        ];
    }

    public function withName(string $name)
    {
        return $this->state(fn () => ['name' => $name]);
    }
}