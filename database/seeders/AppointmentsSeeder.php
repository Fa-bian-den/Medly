<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\Center;
use App\Models\Service;
use App\Models\User;

class AppointmentsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        DB::transaction(function () use ($faker) {
            // Pacientes
            $patients = User::role('paciente')->pluck('id')->all();
            if (empty($patients)) {
                $p = User::firstOrCreate(
                    ['email' => 'patient.seed@example.com'],
                    ['first_name' => 'Paciente', 'last_name' => 'Seed', 'password' => bcrypt('password'), 'status' => 'active']
                );
                $p->assignRole('paciente');
                $patients[] = $p->id;
            }

            // Doctores
            $doctors = User::role('doctor')->pluck('id')->all();
            if (empty($doctors)) {
                $d = User::firstOrCreate(
                    ['email' => 'doctor.seed@example.com'],
                    ['first_name' => 'Doctor', 'last_name' => 'Seed', 'password' => bcrypt('password'), 'status' => 'active']
                );
                $d->assignRole('doctor');
                $doctors[] = $d->id;
            }

            // Centros y servicios mínimos
            $centers = Center::pluck('id')->all();
            if (empty($centers)) {
                $center = Center::firstOrCreate(['name' => 'Centro Seed'], ['municipality_id' => 1, 'address' => 'Seed address']);
                $centers[] = $center->id;
            }

            $services = Service::pluck('id')->all();
            if (empty($services)) {
                $svc = Service::firstOrCreate(['code' => 'SRV-CONS-GEN'], ['name' => 'Consulta General', 'description' => 'Consulta médica general']);
                $services[] = $svc->id;
            }

            // Recolectar o crear slots mínimos
            $slots = AppointmentSlot::pluck('id')->all();
            if (empty($slots)) {
                foreach (array_slice($centers, 0, 3) as $centerId) {
                    foreach (array_slice($services, 0, 3) as $serviceId) {
                        $doctorId = $faker->randomElement($doctors);
                        $date = Carbon::now()->addDays($faker->numberBetween(0, 7))->toDateString();
                        $start = '09:00:00';
                        $end   = Carbon::parse($start)->addMinutes(15)->format('H:i:s');

                        $slot = AppointmentSlot::firstOrCreate(
                            [
                                'center_id'  => $centerId,
                                'user_id'    => $doctorId,
                                'service_id' => $serviceId,
                                'date'       => $date,
                                'start_time' => $start,
                            ],
                            [
                                'end_time' => $end,
                                'capacity' => 1,
                            ]
                        );

                        $slots[] = $slot->id;
                    }
                }
            }

            // Estados permitidos según migración (exactos)
            $statuses = ['pendiente', 'confirmado', 'reprogramado', 'atendido', 'cancelado', 'no se presento'];

            // Crear entre 30 y 60 citas
            $count = $faker->numberBetween(30, 60);
            for ($i = 0; $i < $count; $i++) {
                $patientId = $faker->randomElement($patients);
                $doctorId  = $faker->randomElement($doctors);
                $centerId  = $faker->randomElement($centers);
                $serviceId = $faker->randomElement($services);

                // Intentar obtener un slot existente que coincida
                $slot = AppointmentSlot::where('center_id', $centerId)
                    ->where('service_id', $serviceId)
                    ->where('user_id', $doctorId)
                    ->inRandomOrder()
                    ->first();

                if (! $slot) {
                    $date = Carbon::now()->addDays($faker->numberBetween(0, 10))->toDateString();
                    $start = $faker->randomElement(['08:30:00','09:00:00','10:00:00','14:00:00','15:00:00']);
                    $end = Carbon::parse($start)->addMinutes(15)->format('H:i:s');

                    $slot = AppointmentSlot::create([
                        'center_id'  => $centerId,
                        'user_id'    => $doctorId,
                        'service_id' => $serviceId,
                        'date'       => $date,
                        'start_time' => $start,
                        'end_time'   => $end,
                        'capacity'   => 1,
                    ]);
                }

                // scheduled_at a partir del slot (datetime)
                $slotDate = $slot->date instanceof \Carbon\Carbon
                ? $slot->date->format('Y-m-d')
                : Carbon::parse($slot->date)->format('Y-m-d');

                $scheduledAt = Carbon::parse($slotDate . ' ' . $slot->start_time);


                // Elegir estado válido
                $status = $faker->randomElement($statuses);

                // created_by: prefiera admin si existe, sino doctor
                $creator = User::role('admin')->inRandomOrder()->first() ?? User::find($doctorId);

                $appointmentData = [
                    'patient_id'         => $patientId,
                    'user_id'            => $doctorId,
                    'center_id'          => $centerId,
                    'service_id'         => $serviceId,
                    'slot_id'            => $slot->id,
                    'scheduled_at'       => $scheduledAt->toDateTimeString(),
                    'status'             => $status,
                    'reason'             => $faker->optional()->sentence(),
                    'created_by'         => $creator ? $creator->id : $doctorId,
                    'cancelled_by'       => null,
                    'cancellation_reason'=> null,
                ];

                // Si la cita está cancelada, rellenar campos de cancelación y ajustar fecha
                if ($status === 'cancelado') {
                    $cancelledByIsPatient = $faker->boolean(60);
                    $cancelledBy = $cancelledByIsPatient ? $patientId : ($creator ? $creator->id : $doctorId);
                    $appointmentData['cancelled_by'] = $cancelledBy;
                    $appointmentData['cancellation_reason'] = $faker->sentence(6);
                    $appointmentData['scheduled_at'] = Carbon::parse($appointmentData['scheduled_at'])->addDays($faker->numberBetween(-10, 5))->toDateTimeString();
                }

                // Si la cita fue atendida o no se presentó, poner scheduled_at en pasado reciente
                if (in_array($status, ['atendido', 'no se presento'])) {
                    $appointmentData['scheduled_at'] = Carbon::now()->subDays($faker->numberBetween(0, 15))
                        ->setTime($scheduledAt->hour, $scheduledAt->minute)
                        ->toDateTimeString();
                }

                // Idempotencia: patient + doctor + scheduled_at
                Appointment::updateOrCreate(
                    [
                        'patient_id'   => $appointmentData['patient_id'],
                        'user_id'      => $appointmentData['user_id'],
                        'scheduled_at' => $appointmentData['scheduled_at'],
                    ],
                    $appointmentData
                );
            }
        });
    }
}