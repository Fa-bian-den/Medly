<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CenterService;
use App\Models\Center;
use App\Models\Service;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CenterService>
 */
class CenterServiceFactory extends Factory
{
    protected $model = CenterService::class;

    public function definition(): array
    {
        return [
            'center_id'  => Center::inRandomOrder()->value('id') ?? Center::factory(),
            'service_id' => Service::inRandomOrder()->value('id') ?? Service::factory(),
            'price'      => $this->faker->randomFloat(2, 50, 5000), // formato decimal
            'active'     => $this->faker->boolean(85),
        ];
    }

    // Vincular centro existente
    public function forCenter($center)
    {
        return $this->state(fn () => [
            'center_id' => $center instanceof Center ? $center->id : $center,
        ]);
    }

    // Vincular servicio existente
    public function forService($service)
    {
        return $this->state(fn () => [
            'service_id' => $service instanceof Service ? $service->id : $service,
        ]);
    }

    // Forzar precio
    public function withPrice(float $price)
    {
        return $this->state(fn () => ['price' => number_format($price, 2, '.', '')]);
    }

    public function active()
    {
        return $this->state(fn () => ['active' => true]);
    }

    public function inactive()
    {
        return $this->state(fn () => ['active' => false]);
    }
}