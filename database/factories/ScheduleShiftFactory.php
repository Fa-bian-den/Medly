<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\ScheduleShift;
use App\Models\Schedule;
use App\Models\AppointmentSlot;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScheduleShift>
 */
class ScheduleShiftFactory extends Factory
{
    protected $model = ScheduleShift::class;

    public function definition(): array
    {
        // Elegir una fecha base para calcular horas (solo para generar horas razonables)
        $baseDate = now()->toDateString();

        // Hora de inicio entre 06:00 AM y 06:00 PM en saltos de 15 minutos
        $hour = $this->faker->numberBetween(6, 18);
        $minute = $this->faker->randomElement([0, 15, 30, 45]);
        $start = Carbon::createFromFormat('Y-m-d H:i:s', "{$baseDate} " . sprintf('%02d:%02d:00', $hour, $minute));

        // Duración del turno: entre 1 y 6 horas
        $shiftHours = $this->faker->numberBetween(1, 6);
        $end = (clone $start)->addHours($shiftHours);

        return [
            'schedule_id'            => Schedule::inRandomOrder()->value('id') ?? Schedule::factory(),
            'start_time'             => $start->format('H:i:s'),
            'end_time'               => $end->format('H:i:s'),
            'slot_duration_minutes'  => $this->faker->randomElement([10, 15, 20, 30, 45, 60]),
            'capacity'               => $this->faker->numberBetween(1, 3),
            'break_minutes'          => $this->faker->randomElement([0, 10, 15, 20]),
            'active'                 => $this->faker->boolean(95),
        ];
    }

    /**
     * Forzar schedule (Schedule instance o id)
     */
    public function forSchedule($schedule)
    {
        return $this->state(fn () => [
            'schedule_id' => $schedule instanceof Schedule ? $schedule->id : $schedule,
        ]);
    }

    /**
     * Ajustar duración de cada slot en minutos
     */
    public function slotDuration(int $minutes)
    {
        return $this->state(fn () => [
            'slot_duration_minutes' => $minutes,
        ]);
    }

    /**
     * Ajustar capacidad del turno
     */
    public function capacity(int $count)
    {
        return $this->state(fn () => [
            'capacity' => $count,
        ]);
    }

    /**
     * Añadir minutos de pausa dentro del turno
     */
    public function breakMinutes(int $minutes)
    {
        return $this->state(fn () => [
            'break_minutes' => $minutes,
        ]);
    }

    /**
     * Marcar turno como inactivo
     */
    public function inactive()
    {
        return $this->state(fn () => ['active' => false]);
    }

    /**
     * Opción útil en seeders/tests: generar automáticamente AppointmentSlot(s)
     * para un rango de días a partir de la fecha actual.
     *
     * Nota: intenta recuperar schedule->center_id y schedule->doctor_id; si no están
     * disponibles o faltan modelos relacionados, la creación se silencia para no romper seeds.
     */
    public function withSlots(int $days = 7)
    {
        return $this->afterCreating(function (ScheduleShift $shift) use ($days) {
            try {
                $schedule = $shift->schedule()->first();
                if (! $schedule) {
                    return;
                }

                $centerId = $schedule->center_id ?? null;
                $doctorId = $schedule->doctor_id ?? null;

                if (! $centerId || ! $doctorId) {
                    return;
                }

                // Generar slots para los próximos $days días respetando start/end/duration/break
                $today = now()->startOfDay();
                for ($d = 0; $d < $days; $d++) {
                    $date = $today->copy()->addDays($d)->toDateString();

                    // Construir datetime de inicio y fin usando la hora del turno
                    $startDt = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $shift->start_time);
                    $endDt = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $shift->end_time);

                    $slotDuration = max(1, (int) $shift->slot_duration_minutes);
                    $break = (int) $shift->break_minutes;

                    $cursor = $startDt->copy();
                    while ($cursor->lt($endDt)) {
                        $slotEnd = $cursor->copy()->addMinutes($slotDuration);
                        if ($slotEnd->gt($endDt)) {
                            break;
                        }

                        // Crear slot usando factory; silenciar posibles fallos si la tabla/modelo no existen
                        try {
                            AppointmentSlot::factory()->create([
                                'center_id'  => $centerId,
                                'user_id'    => $doctorId,
                                'service_id' => $schedule->service_id ?? null,
                                'date'       => $date,
                                'start_time' => $cursor->format('H:i:s'),
                                'end_time'   => $slotEnd->format('H:i:s'),
                                'capacity'   => $shift->capacity,
                            ]);
                        } catch (\Throwable $e) {
                            // Silenciar creación de slot para entornos incompletos
                        }

                        // Avanzar cursor respetando break entre bloques
                        $cursor->addMinutes($slotDuration + $break);
                    }
                }
            } catch (\Throwable $e) {
                // Silenciar errores de creación en entornos parciales/seeds rápidos
            }
        });
    }
}