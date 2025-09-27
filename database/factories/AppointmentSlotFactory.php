<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Center;
use App\Models\User;
use App\Models\Service;
use App\Models\AppointmentSlot;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppointmentSlot>
 */
class AppointmentSlotFactory extends Factory
{
    protected $model = AppointmentSlot::class;

    public function definition(): array
    {
        // Fecha entre 1 y 14 días en el futuro
        $dt = $this->faker->dateTimeBetween('+1 days', '+14 days');
        $date = $dt->format('Y-m-d');

        // Hora de inicio: entre 08:00 y 17:30 en múltiplos de 15 minutos
        $hour = $this->faker->numberBetween(8, 17);
        $minutes = $this->faker->randomElement([0, 15, 30, 45]);
        $start = Carbon::createFromFormat('Y-m-d H:i:s', "{$date} " . sprintf('%02d:%02d:00', $hour, $minutes));

        // Duración del slot en minutos (comúnmente 15/20/30/45/60)
        $duration = $this->faker->randomElement([15, 20, 30, 45, 60]);
        $end = (clone $start)->addMinutes($duration);

        return [
            'center_id'  => Center::inRandomOrder()->value('id') ?? Center::factory(),
            'user_id'    => User::factory()->doctor(),
            'service_id' => Service::factory(),
            'date'       => $date,
            'start_time' => $start->format('H:i:s'),
            'end_time'   => $end->format('H:i:s'),
            'capacity'   => $this->faker->optional(0.95)->numberBetween(1, 3) ?? 1,
        ];
    }

    /**
     * Forzar centro específico
     */
    public function forCenter($center)
    {
        return $this->state(fn () => [
            'center_id' => $center instanceof Center ? $center->id : $center,
        ]);
    }

    /**
     * Forzar doctor específico (user_id)
     */
    public function forDoctor($doctor)
    {
        return $this->state(fn () => [
            'user_id' => $doctor instanceof User ? $doctor->id : $doctor,
        ]);
    }

    /**
     * Forzar servicio específico
     */
    public function forService($service)
    {
        return $this->state(fn () => [
            'service_id' => $service instanceof Service ? $service->id : $service,
        ]);
    }

    /**
     * Estado: slot marcado como disabled (no disponible)
     */
    public function disabled()
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => 0,
        ]);
    }

    /**
     * Estado: capacidad agotada
     */
    public function exhausted()
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => 0,
        ]);
    }
}