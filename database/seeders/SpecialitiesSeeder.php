<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Speciality;
use Carbon\Carbon;

class SpecialitiesSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $items = [
            ['name' => 'Medicina General', 'code' => 'GEN', 'description' => 'Atención primaria y general'],
            ['name' => 'Cardiología', 'code' => 'CARD', 'description' => 'Enfermedades del corazón'],
            ['name' => 'Pediatría', 'code' => 'PED', 'description' => 'Atención a niños'],
            ['name' => 'Ginecología', 'code' => 'GYN', 'description' => 'Salud femenina y obstetricia'],
            ['name' => 'Dermatología', 'code' => 'DERM', 'description' => 'Piel y anexos'],
            ['name' => 'Traumatología', 'code' => 'ORTH', 'description' => 'Aparato locomotor'],
            ['name' => 'Neurología', 'code' => 'NEU', 'description' => 'Sistema nervioso'],
            ['name' => 'Psiquiatría', 'code' => 'PSY', 'description' => 'Salud mental'],
            ['name' => 'Endocrinología', 'code' => 'ENDO', 'description' => 'Hormonas y metabolismo'],
            ['name' => 'Oftalmología', 'code' => 'OPHT', 'description' => 'Ojos'],
            ['name' => 'ORL', 'code' => 'ENT', 'description' => 'Oído, nariz y garganta'],
            ['name' => 'Urología', 'code' => 'URO', 'description' => 'Sistema urinario y reproductor masculino'],
            ['name' => 'Nefrología', 'code' => 'NEPH', 'description' => 'Riñón'],
            ['name' => 'Oncología', 'code' => 'ONC', 'description' => 'Cáncer'],
            ['name' => 'Odontología', 'code' => 'DENT', 'description' => 'Salud bucal'],
        ];

        foreach ($items as $row) {
            Speciality::updateOrCreate(
                ['name' => $row['name']],
                array_merge($row, ['created_at' => $now, 'updated_at' => $now])
            );
        }
    }
}