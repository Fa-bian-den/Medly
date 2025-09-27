<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Models\Center;
use App\Models\Service;
use App\Models\User;

class AppointmentSlotSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Ajustes controlables para volumen y performance
        $daysAhead = 10;
        $dailyWindows = [
            ['09:00:00', '12:00:00'],
            ['14:00:00', '17:00:00'],
        ];
        $slotMinutes = 15;
        $batchSize = 1000; // flush cada N filas

        DB::transaction(function () use ($faker, $daysAhead, $dailyWindows, $slotMinutes, $batchSize) {
            $centerIds = Center::pluck('id')->all();
            $serviceIds = Service::pluck('id')->all();
            $doctorIds = User::role('doctor')->pluck('id')->all();

            if (empty($centerIds) || empty($serviceIds) || empty($doctorIds)) {
                return;
            }

            $rows = [];
            $now = Carbon::now();

            // Helper para convertir H:i:s a segundos desde medianoche
            $timeToSec = function (string $t) {
                [$h, $m, $s] = array_pad(explode(':', $t), 3, '0');
                return ($h * 3600) + ($m * 60) + $s;
            };

            foreach ($centerIds as $centerId) {
                // sample de servicios para este centro (reduce combinaciones)
                $serviceSubset = $faker->randomElements($serviceIds, $faker->numberBetween(2, min(6, count($serviceIds))));

                foreach ($serviceSubset as $serviceId) {
                    // sample de 1-3 doctores por centro+servicio
                    $assignedDoctors = $faker->randomElements($doctorIds, $faker->numberBetween(1, min(3, count($doctorIds))));

                    for ($d = 0; $d <= $daysAhead; $d++) {
                        $date = $now->copy()->addDays($d)->toDateString();

                        foreach ($dailyWindows as $window) {
                            $windowStartSec = $timeToSec($window[0]);
                            $windowEndSec = $timeToSec($window[1]);

                            for ($cursor = $windowStartSec; $cursor + ($slotMinutes * 60) <= $windowEndSec; $cursor += ($slotMinutes * 60)) {
                                $slotStart = gmdate('H:i:s', $cursor);
                                $slotEnd = gmdate('H:i:s', $cursor + ($slotMinutes * 60));

                                foreach ($assignedDoctors as $doctorId) {
                                    $rows[] = [
                                        'center_id'  => $centerId,
                                        'user_id'    => $doctorId,
                                        'service_id' => $serviceId,
                                        'date'       => $date,
                                        'start_time' => $slotStart,
                                        'end_time'   => $slotEnd,
                                        'capacity'   => $faker->numberBetween(1, 3),
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ];

                                    if (count($rows) >= $batchSize) {
                                        DB::table('appointment_slots')->upsert(
                                            $rows,
                                            ['center_id','user_id','service_id','date','start_time'],
                                            ['end_time','capacity','updated_at']
                                        );
                                        $rows = [];
                                    }
                                }
                            }
                        }

                        // bloque vespertino ocasional con probabilidad reducida
                        if ($faker->boolean(12)) {
                            $pvStart = 18 * 3600;
                            $pvEnd = 20 * 3600;
                            for ($cursor = $pvStart; $cursor + (30*60) <= $pvEnd; $cursor += (30*60)) {
                                $slotStart = gmdate('H:i:s', $cursor);
                                $slotEnd = gmdate('H:i:s', $cursor + 30*60);
                                foreach ($assignedDoctors as $doctorId) {
                                    $rows[] = [
                                        'center_id'  => $centerId,
                                        'user_id'    => $doctorId,
                                        'service_id' => $serviceId,
                                        'date'       => $date,
                                        'start_time' => $slotStart,
                                        'end_time'   => $slotEnd,
                                        'capacity'   => $faker->numberBetween(1, 2),
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ];
                                    if (count($rows) >= $batchSize) {
                                        DB::table('appointment_slots')->upsert(
                                            $rows,
                                            ['center_id','user_id','service_id','date','start_time'],
                                            ['end_time','capacity','updated_at']
                                        );
                                        $rows = [];
                                    }
                                }
                            }
                        }
                    } // days
                } // services
            } // centers

            // Flush final
            if (! empty($rows)) {
                DB::table('appointment_slots')->upsert(
                    $rows,
                    ['center_id','user_id','service_id','date','start_time'],
                    ['end_time','capacity','updated_at']
                );
            }
        });
    }
}