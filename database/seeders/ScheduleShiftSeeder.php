<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\ScheduleShift;

class ScheduleShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        DB::transaction(function () use ($faker) {
            $schedules = Schedule::all();

            // Si no hay schedules, salir sin hacer nada
            if ($schedules->isEmpty()) {
                return;
            }

            foreach ($schedules as $schedule) {
                // Normalizar nombre base para clave idempotente
                $baseName = 'auto-' . $schedule->id;

                // Si el schedule es one_time, crear un turno único
                if ($schedule->recurrence_type === 'one_time') {
                    $this->createOrUpdateShift(
                        $schedule->id,
                        "{$baseName}-one",
                        '09:00:00',
                        '12:00:00',
                        $faker->randomElement([15,20,30]),
                        $faker->numberBetween(1,4),
                        $faker->randomElement([0,10,15]),
                        true
                    );
                    continue;
                }

                // Turno mañana por defecto
                $this->createOrUpdateShift(
                    $schedule->id,
                    "{$baseName}-morning",
                    '08:00:00',
                    '12:00:00',
                    $faker->randomElement([10,15,20]),
                    $faker->numberBetween(1,4),
                    10,
                    true
                );

                // Turno tarde por defecto
                $this->createOrUpdateShift(
                    $schedule->id,
                    "{$baseName}-afternoon",
                    '14:00:00',
                    '18:00:00',
                    $faker->randomElement([15,20,30]),
                    $faker->numberBetween(1,4),
                    15,
                    $faker->boolean(90)
                );

                // Ocasionalmente añadir un turno nocturno o extended
                if ($faker->boolean(20)) {
                    $this->createOrUpdateShift(
                        $schedule->id,
                        "{$baseName}-evening",
                        '18:30:00',
                        '21:30:00',
                        $faker->randomElement([15,30]),
                        $faker->numberBetween(1,3),
                        0,
                        $faker->boolean(80)
                    );
                }

                // Añadir 0-2 shifts personalizados aleatorios para pruebas
                $extra = $faker->numberBetween(0, 2);
                for ($i = 0; $i < $extra; $i++) {
                    $startHour = $faker->numberBetween(7, 16);
                    $start = Carbon::createFromTime($startHour, $faker->randomElement([0,15,30,45]))->format('H:i:s');
                    $end = Carbon::createFromTime($startHour + $faker->numberBetween(1,4), 0)->format('H:i:s');

                    $this->createOrUpdateShift(
                        $schedule->id,
                        "{$baseName}-extra-{$i}",
                        $start,
                        $end,
                        $faker->randomElement([10,15,20,30]),
                        $faker->numberBetween(1,5),
                        $faker->randomElement([0,5,10]),
                        $faker->boolean(85)
                    );
                }
            }
        });
    }

    /**
     * Crea o actualiza un ScheduleShift de forma idempotente.
     *
     * @param int $scheduleId
     * @param string $uniqueKey cadena única para evitar duplicados (por ejemplo "auto-{scheduleId}-morning")
     * @param string $startTime formato H:i:s
     * @param string $endTime formato H:i:s
     * @param int $slotDurationMinutes
     * @param int $capacity
     * @param int $breakMinutes
     * @param bool $active
     * @return void
     */
    protected function createOrUpdateShift(
        int $scheduleId,
        string $uniqueKey,
        string $startTime,
        string $endTime,
        int $slotDurationMinutes,
        int $capacity,
        int $breakMinutes,
        bool $active
    ): void {
        // Usamos un campo exclusivo virtual 'seed_key' para idempotencia si no existe en la tabla,
        // si no lo tienes, usamos combinación schedule_id + start_time + end_time como clave única.
        $match = [
            'schedule_id' => $scheduleId,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];

        ScheduleShift::updateOrCreate(
            $match,
            [
                'schedule_id' => $scheduleId,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'slot_duration_minutes' => $slotDurationMinutes,
                'capacity' => $capacity,
                'break_minutes' => $breakMinutes,
                'active' => $active,
                // si tu modelo tiene campos adicionales, agrégalos aquí
            ]
        );
    }
}