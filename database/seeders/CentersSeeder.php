<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Center;
use App\Models\Municipality;

class CentersSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $examples = [
            ['name' => 'Hospital Regional San Juan', 'address' => 'Av. Central 123', 'phone' => '505-2222-0000', 'municipality' => 'San Carlos', 'url' => null, 'ruc' => null, 'is_public' => true],
            ['name' => 'Centro de Salud Estelí', 'address' => 'Calle Principal s/n', 'phone' => '505-2777-1111', 'municipality' => 'Estelí', 'url' => null, 'ruc' => null, 'is_public' => true],
            ['name' => 'Clínica Managua', 'address' => 'Km 8 Carretera Norte', 'phone' => '505-2266-2222', 'municipality' => 'Managua', 'url' => null, 'ruc' => null, 'is_public' => false],
            ['name' => 'Centro Médico León', 'address' => 'Boulevard Centro', 'phone' => '505-2333-3333', 'municipality' => 'León', 'url' => null, 'ruc' => null, 'is_public' => false],
            ['name' => 'Policlínico Masaya', 'address' => 'Plaza Central', 'phone' => '505-2555-4444', 'municipality' => 'Masaya', 'url' => null, 'ruc' => null, 'is_public' => false],
            ['name' => 'Clínica Granada', 'address' => 'Calle La Calzada', 'phone' => '505-2588-5555', 'municipality' => 'Granada', 'url' => null, 'ruc' => null, 'is_public' => false],
        ];

        DB::transaction(function () use ($examples, $faker) {
            foreach ($examples as $ex) {
                $mun = Municipality::where('name', 'like', $ex['municipality'] . '%')->first();
                $municipalityId = $mun ? $mun->id : (Municipality::inRandomOrder()->value('id') ?? Municipality::factory()->create()->id);

                Center::updateOrCreate(
                    ['name' => $ex['name'], 'municipality_id' => $municipalityId],
                    [
                        'address'     => $ex['address'],
                        'phone'       => preg_replace('/\D+/', '', $ex['phone']),
                        'url'         => $ex['url'],
                        'ruc'         => $ex['ruc'],
                        'logo'        => null,
                        'is_public'   => $ex['is_public'],
                    ]
                );
            }

            // Centros adicionales aleatorios (sin lat/lng)
            for ($i = 0; $i < 8; $i++) {
                $municipalityId = Municipality::inRandomOrder()->value('id') ?? Municipality::factory()->create()->id;
                Center::firstOrCreate(
                    ['name' => $faker->unique()->company . ' Centro', 'municipality_id' => $municipalityId],
                    [
                        'address'   => $faker->address,
                        'phone'     => preg_replace('/\D+/', '', $faker->phoneNumber),
                        'url'       => $faker->optional()->url,
                        'ruc'       => $faker->optional()->bothify('###########'),
                        'logo'      => null,
                        'is_public' => $faker->boolean(50),
                    ]
                );
            }
        });
    }
}