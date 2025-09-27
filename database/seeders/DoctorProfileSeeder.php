<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Center;
use App\Models\DoctorProfile;
use Spatie\Permission\Models\Role;

class DoctorProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurar rol doctor
        Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web']);

        DB::transaction(function () {
            $examples = [
                [
                    'user' => [
                        'email' => 'dr.maria@example.com',
                        'first_name' => 'María',
                        'last_name' => 'González',
                        'password' => 'password123',
                        'status' => 'active',
                        'email_verified_at' => now(),
                    ],
                    'center_like' => 'Centro de Salud Estelí',
                    'carnet_minsa' => 'MINSA-2025-001',
                    'documents' => [
                        ['type' => 'carnet_minsa', 'file' => null, 'meta' => ['issued' => '2019-06-01']],
                        ['type' => 'titulo', 'file' => null, 'meta' => ['university' => 'UNAN']],
                    ],
                    'ruc' => '0012345678-9',
                    'specialties' => ['Medicina General', 'Urgencias'],
                    'status_validation' => 'aprobado',
                    'reviewed_by_email' => 'admin@example.com',
                    'validated_at' => Carbon::now()->subDays(10),
                    'comments' => 'Validación completa, documentos OK',
                ],
                [
                    'user' => [
                        'email' => 'dr.juan@example.com',
                        'first_name' => 'Juan',
                        'last_name' => 'Pérez',
                        'password' => 'password123',
                        'status' => 'active',
                        'email_verified_at' => now(),
                    ],
                    'center_like' => 'Clínica Managua',
                    'carnet_minsa' => 'MINSA-2025-002',
                    'documents' => [
                        ['type' => 'carnet_minsa', 'file' => null, 'meta' => ['issued' => '2020-02-15']],
                    ],
                    'ruc' => null,
                    'specialties' => ['Pediatría'],
                    'status_validation' => 'pendiente',
                    'reviewed_by_email' => null,
                    'validated_at' => null,
                    'comments' => 'Pendiente revisión de título',
                ],
                [
                    'user' => [
                        'email' => 'dr.luis@example.com',
                        'first_name' => 'Luis',
                        'last_name' => 'Martínez',
                        'password' => 'password123',
                        'status' => 'active',
                        'email_verified_at' => now(),
                    ],
                    'center_like' => 'Hospital Regional San Juan',
                    'carnet_minsa' => 'MINSA-2025-003',
                    'documents' => [
                        ['type' => 'carnet_minsa', 'file' => null, 'meta' => ['issued' => '2018-04-20']],
                        ['type' => 'certificado', 'file' => null, 'meta' => ['note' => 'documento incompleto']],
                    ],
                    'ruc' => '0098765432-1',
                    'specialties' => ['Ginecología y Obstetricia'],
                    'status_validation' => 'rechazado',
                    'reviewed_by_email' => 'admin@example.com',
                    'validated_at' => Carbon::now()->subDays(5),
                    'comments' => 'Documentos incompletos, solicitar títulos originales',
                ],
            ];

            foreach ($examples as $ex) {
                // Preparar y crear/actualizar user (idempotente)
                $userData = $ex['user'];
                $email = $userData['email'];
                $rawPassword = $userData['password'] ?? 'password123';
                $password = str_starts_with($rawPassword, '$2y$') ? $rawPassword : Hash::make($rawPassword);

                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'first_name' => $userData['first_name'],
                        'last_name' => $userData['last_name'],
                        'password' => $password,
                        'status' => $userData['status'] ?? 'active',
                        'email_verified_at' => $userData['email_verified_at'] ?? null,
                    ]
                );

                // Asignar rol doctor si hace falta
                if (! $user->hasRole('doctor')) {
                    $user->assignRole('doctor');
                }

                // Resolver center propuesto por nombre parcial
                $center = null;
                if (! empty($ex['center_like'])) {
                    $center = Center::where('name', 'like', '%' . $ex['center_like'] . '%')->first();
                }
                $centerId = $center ? $center->id : null;

                // Resolver reviewer si existe
                $reviewedById = null;
                if (! empty($ex['reviewed_by_email'])) {
                    $reviewer = User::where('email', $ex['reviewed_by_email'])->first();
                    $reviewedById = $reviewer ? $reviewer->id : null;
                }

                // Crear o actualizar perfil de doctor (idempotente)
                DoctorProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'user_id' => $user->id,
                        'center_id_proposed' => $centerId,
                        'carnet_minsa' => $ex['carnet_minsa'] ?? null,
                        'documents' => $ex['documents'] ?? null,
                        'ruc' => $ex['ruc'] ?? null,
                        'specialties' => $ex['specialties'] ?? null,
                        'status_validation' => $ex['status_validation'] ?? 'pendiente',
                        'reviewed_by' => $reviewedById,
                        'validated_at' => $ex['validated_at'] ?? null,
                        'comments' => $ex['comments'] ?? null,
                    ]
                );
            }
        });
    }
}