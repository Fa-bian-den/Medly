<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Service;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Consulta General',        'code' => 'SRV-CONS-GEN',    'description' => 'Consulta médica general'],
            ['name' => 'Pediatría',               'code' => 'SRV-PED',         'description' => 'Atención médica pediátrica'],
            ['name' => 'Odontología',             'code' => 'SRV-ODT',         'description' => 'Cuidado dental y procedimientos odontológicos'],
            ['name' => 'Ginecología y Obstetricia','code' => 'SRV-GYN',        'description' => 'Atención ginecológica y obstétrica'],
            ['name' => 'Psicología',              'code' => 'SRV-PSY',         'description' => 'Consultas y terapia psicológica'],
            ['name' => 'Laboratorio Clínico',     'code' => 'SRV-LAB',         'description' => 'Análisis de laboratorio y pruebas diagnósticas'],
            ['name' => 'Radiología',              'code' => 'SRV-RAD',         'description' => 'Imagenología: rayos X, ecografía, etc.'],
            ['name' => 'Vacunación',              'code' => 'SRV-VAC',         'description' => 'Aplicación de vacunas y control de inmunizaciones'],
            ['name' => 'Urgencias',               'code' => 'SRV-URG',         'description' => 'Atención de emergencias'],
            ['name' => 'Teleconsulta',            'code' => 'SRV-TEL',         'description' => 'Consulta remota por videollamada o teléfono'],
        ];

        DB::transaction(function () use ($services) {
            foreach ($services as $s) {
                Service::updateOrCreate(
                    ['code' => strtoupper($s['code'])],
                    [
                        'name' => $s['name'],
                        'description' => $s['description'],
                    ]
                );
            }
        });
    }
}