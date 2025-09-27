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
    protected $model = Center::class;

    public function definition(): array
    {
        // Reusar un municipio existente o crear uno nuevo
        $municipalityId = Municipality::inRandomOrder()->value('id') ?? Municipality::factory();

        return [
            'municipality_id' => $municipalityId,
            'name'            => $this->faker->company() . ' Centro de Salud',
            'logo'            => $this->faker->optional(0.6)->imageUrl(300, 300, 'business', true, 'logo'),
            'address'         => $this->faker->address(),
            'url'             => $this->faker->optional()->url(),
            'phone'           => preg_replace('/\D+/', '', $this->faker->phoneNumber()),
            'ruc'             => $this->faker->unique()->bothify('##########'), // ajustar formato local si se requiere
            'is_public'       => $this->faker->boolean(70),
        ];
    }

    /**
     * Asigna explícitamente un municipio
     */
    public function forMunicipality($municipality)
    {
        return $this->state(fn () => [
            'municipality_id' => $municipality instanceof Municipality ? $municipality->id : $municipality,
        ]);
    }
}