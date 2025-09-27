<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Center;
use App\Models\Service;
use App\Models\CenterService;

class CenterServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        DB::transaction(function () use ($faker) {
            $centers = Center::pluck('id')->all();
            $services = Service::pluck('id')->all();

            // Si no hay centros o servicios, no hacer nada
            if (empty($centers) || empty($services)) {
                return;
            }

            // Asociar cada centro con una selección de servicios aleatoria (4-8)
            foreach ($centers as $centerId) {
                $serviceSubset = $faker->randomElements($services, $faker->numberBetween(4, min(8, count($services))));

                foreach ($serviceSubset as $serviceId) {
                    $price = $faker->randomFloat(2, 50, 5000);
                    CenterService::updateOrCreate(
                        ['center_id' => $centerId, 'service_id' => $serviceId],
                        [
                            'price'  => $price,
                            'active' => $faker->boolean(90),
                        ]
                    );
                }
            }

            // Asegurar algunas combinaciones explícitas útiles para pruebas
            $examplePairs = [
                // [center_name_like, service_code, price]
                ['Hospital', 'SRV-URG', 0.00],
                ['Centro de Salud', 'SRV-CONS-GEN', 150.00],
                ['Clínica', 'SRV-LAB', 300.00],
                ['Policlínico', 'SRV-VAC', 50.00],
            ];

            foreach ($examplePairs as [$centerLike, $serviceCode, $price]) {
                $center = Center::where('name', 'like', "%{$centerLike}%")->first();
                $service = Service::where('code', $serviceCode)->first();
                if ($center && $service) {
                    CenterService::updateOrCreate(
                        ['center_id' => $center->id, 'service_id' => $service->id],
                        ['price' => number_format($price, 2, '.', ''), 'active' => true]
                    );
                }
            }
        });
    }
}