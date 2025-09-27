<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Municipality;
use App\Models\Departament;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Municipality>
 */
class MunicipalityFactory extends Factory
{
    protected $model = Municipality::class;

    public function definition(): array
    {
        return [
            'name'             => $this->faker->city(),
            'departament_id'   => Departament::inRandomOrder()->value('id') ?? Departament::factory(),
        ];
    }

    /**
     * Forzar department (Department instance o id)
     */
    public function forDepartament($department)
    {
        return $this->state(fn () => [
            'departament_id' => $department instanceof Departament ? $department->id : $department,
        ]);
    }

    /**
     * Asignar nombre específico
     */
    public function withName(string $name)
    {
        return $this->state(fn () => ['name' => $name]);
    }
}