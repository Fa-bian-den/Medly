<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Center;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        // Tipo de recurrencia por defecto
        $recurrenceType = $this->faker->randomElement(['none', 'daily', 'weekly', 'monthly']);

        // Si es weekly, generamos entre 1 y 5 dias
        $recurrenceDays = null;
        if ($recurrenceType === 'weekly') {
            $days = $this->faker->randomElements(range(1, 7), $this->faker->numberBetween(1, 5));
            sort($days);
            $recurrenceDays = $days;
        } elseif ($recurrenceType === 'monthly') {
            // Para monthly podemos guardar días del mes (1..28) o null para "día relativo"
            $recurrenceDays = [$this->faker->numberBetween(1, 28)];
        }

        // Rango por defecto: inicio hoy o hace pocos días, fin dentro de 1-6 meses (o null)
        $start = Carbon::now()->subDays($this->faker->numberBetween(0, 7))->toDateString();
        $end = $this->faker->boolean(70) ? Carbon::parse($start)->addMonths($this->faker->numberBetween(1, 6))->toDateString() : null;

        return [
            'name'            => $this->faker->optional()->company() . ' Horario',
            'recurrence_type' => $recurrenceType,
            'recurrence_days' => $recurrenceDays, // casteado a json en el modelo/migración
            'start_date'      => $start,
            'end_date'        => $end,
            'active'          => $this->faker->boolean(90),
            'doctor_id'       => User::inRandomOrder()->whereNotNull('id')->value('id') ?? User::factory()->doctor(),
            'center_id'       => Center::inRandomOrder()->value('id') ?? Center::factory(),
        ];
    }

    /**
     * Forzar doctor (User instance o id).
     */
    public function forDoctor($doctor)
    {
        return $this->state(fn () => [
            'doctor_id' => $doctor instanceof User ? $doctor->id : $doctor,
        ]);
    }

    /**
     * Forzar centro (Center instance o id).
     */
    public function forCenter($center)
    {
        return $this->state(fn () => [
            'center_id' => $center instanceof Center ? $center->id : $center,
        ]);
    }

    /**
     * Estado: sin recurrencia.
     */
    public function none()
    {
        return $this->state(fn () => [
            'recurrence_type' => 'none',
            'recurrence_days' => null,
        ]);
    }

    /**
     * Estado: diario.
     */
    public function daily()
    {
        return $this->state(fn () => [
            'recurrence_type' => 'daily',
            'recurrence_days' => null,
        ]);
    }

    /**
     * Estado: semanal con días especificados.
     * $days = array de enteros ISO 1=Mon .. 7=Sun
     */
    public function weekly(?array $days = null)
    {
        return $this->state(fn () => [
            'recurrence_type' => 'weekly',
            'recurrence_days' => $days ?? [1, 3, 5],
        ]);
    }

    /**
     * $days = array de enteros 1..28
     */
    public function monthly(?array $days = null)
    {
        return $this->state(fn () => [
            'recurrence_type' => 'monthly',
            'recurrence_days' => $days ?? [1],
        ]);
    }

    /**
     * Forzar rango de fechas
     */
    public function withinDates(string $startDate, ?string $endDate = null)
    {
        return $this->state(fn () => [
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ]);
    }

    /**
     * Activar / desactivar schedule
     */
    public function active()
    {
        return $this->state(fn () => ['active' => true]);
    }

    public function inactive()
    {
        return $this->state(fn () => ['active' => false]);
    }
}