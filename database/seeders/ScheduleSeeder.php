<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Center;
use Spatie\Permission\Models\Role;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        // Asegurar que exista el role doctor
        Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web']);

        DB::transaction(function () use ($faker) {
            // Obtener doctores; si no hay, crear algunos de ejemplo
            $doctorIds = User::role('doctor')->pluck('id')->all();
            if (empty($doctorIds)) {
                $sampleDoctors = [
                    ['email' => 'dr.seed1@example.com', 'first_name' => 'Seed', 'last_name' => 'Doctor1', 'password' => bcrypt('password')],
                    ['email' => 'dr.seed2@example.com', 'first_name' => 'Seed', 'last_name' => 'Doctor2', 'password' => bcrypt('password')],
                ];
                foreach ($sampleDoctors as $sd) {
                    $u = User::firstOrCreate(['email' => $sd['email']], $sd);
                    if (! $u->hasRole('doctor')) {
                        $u->assignRole('doctor');
                    }
                    $doctorIds[] = $u->id;
                }
            }

            $centerIds = Center::pluck('id')->all();
            if (empty($centerIds)) {
                // Si no hay centros, crear uno mínimo para asignar horarios
                $center = Center::firstOrCreate(['name' => 'Centro Seed'], ['municipality_id' => 1, 'address' => 'Seed address']);
                $centerIds[] = $center->id;
            }

            // Tipos de recurrencia soportados
            $recurrenceTypes = ['semanal', 'diario', 'ninguno', 'mensual'];

            foreach ($doctorIds as $doctorId) {
                // Crear entre 1 y 3 horarios por doctor
                $count = $faker->numberBetween(1, 3);
                for ($i = 0; $i < $count; $i++) {
                    $centerId = $faker->randomElement($centerIds);
                    $recurrence = $faker->randomElement($recurrenceTypes);
                    $name = match ($recurrence) {
                        'diario' => 'Horario Diario ' . ($i + 1),
                        'semanal' => 'Horario Semanal ' . ($i + 1),
                        'mensual' => 'Horario Mensual ' . ($i + 1),
                        default => 'Horario Puntual ' . ($i + 1),
                    };

                    // Fechas
                    $startDate = Carbon::now()->startOfDay()->addDays($faker->numberBetween(0, 5));
                    $endDate = $recurrence === 'ninguno' ? clone $startDate : (clone $startDate)->addMonths($faker->numberBetween(1, 12));

                    // Días de recurrencia por semana como array de enteros 0-6
                    $recurrenceDays = null;
                    if ($recurrence === 'semanal') {
                        $days = $faker->randomElements([0,1,2,3,4,5,6], $faker->numberBetween(2,5));
                        sort($days);
                        $recurrenceDays = $days;
                    }

                    Schedule::updateOrCreate(
                        [
                            'doctor_id' => $doctorId,
                            'center_id' => $centerId,
                            'name' => $name,
                        ],
                        [
                            'doctor_id' => $doctorId,
                            'center_id' => $centerId,
                            'name' => $name,
                            'recurrence_type' => $recurrence,
                            'recurrence_days' => $recurrenceDays,
                            'start_date' => $startDate->toDateString(),
                            'end_date' => $endDate->toDateString(),
                            'active' => $faker->boolean(90),
                        ]
                    );
                }
            }

            // Ejemplo explícito: horario semanal para dr.maria@example.com si existe
            $maria = User::where('email', 'dr.maria@example.com')->first();
            if ($maria) {
                $center = Center::where('name', 'like', '%Estelí%')->first() ?? Center::inRandomOrder()->first();
                Schedule::updateOrCreate(
                    ['doctor_id' => $maria->id, 'center_id' => $center->id, 'name' => 'Horario Semanal Mañana'],
                    [
                        'recurrence_type' => 'semanal',
                        'recurrence_days' => [1,2,3,4,5],
                        'start_date' => Carbon::now()->toDateString(),
                        'end_date' => Carbon::now()->addMonths(6)->toDateString(),
                        'active' => true,
                    ]
                );
            }
        });
    }
}