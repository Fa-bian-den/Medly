<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Service;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->unique()->words(2, true),
            'code'        => strtoupper($this->faker->unique()->bothify('SRV-???-####')),
            'description' => $this->faker->optional()->sentence(),
        ];
    }

    public function withCode(string $code)
    {
        return $this->state(fn () => ['code' => $code]);
    }

    public function withName(string $name)
    {
        return $this->state(fn () => ['name' => $name]);
    }

    public function withDescription(string $description)
    {
        return $this->state(fn () => ['description' => $description]);
    }
}