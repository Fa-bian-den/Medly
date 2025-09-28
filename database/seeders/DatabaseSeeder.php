<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // usuario de prueba (idempotente)
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'first_name' => 'Test',
                'last_name'  => 'User',
                'password'   => bcrypt('secret'), // cambiar si hace falta
                'status'     => 'active',
                'email_verified_at' => now(),
            ]
        );

        // profile asociado (solo si no existe)
        if (! $user->profile) {
            $user->profile()->create([
                'birthdate' => now()->subYears(30),
                'phone'     => null,
                'address'   => null,
            ]);
        }

        // permisos (siempre primero)
        $this->call([
            PermissionRoleSeeder::class,
        ]);

        // Roles
        $this->call(\Database\Seeders\RoleSeeder::class);

        // crear usuario administrador seguro (seeder idempotente debe usar firstOrCreate/updateOrCreate)
        $this->call([
            \Database\Seeders\CreateAdminUserSeeder::class,
        ]);

        //Departamentos y municipios
        $this->call([
            \Database\Seeders\DepartamentSeeder::class,
        ]);

        //Centros medicos
        $this->call([
            \Database\Seeders\CentersSeeder::class,
        ]);

        //Servicios medicos
        $this->call([
            \Database\Seeders\ServicesSeeder::class,
        ]);

        //Servicios medicos ofrecidos por centros
        $this->call([
            \Database\Seeders\CenterServiceSeeder::class,
        ]);

        //Medicos
        $this->call([
            \Database\Seeders\DoctorProfileSeeder::class,
        ]);

        //Horarios
        $this->call([
            \Database\Seeders\ScheduleSeeder::class,
        ]);

        //Turnos por horarios
        $this->call([
            \Database\Seeders\ScheduleShiftSeeder::class,
        ]);

        //Horario para agendar cita
        $this->call([
            \Database\Seeders\AppointmentSlotSeeder::class,
        ]);

        //Cita
        $this->call([
            \Database\Seeders\AppointmentsSeeder::class,
        ]);

        //Especialidades
        $this->call([
            \Database\Seeders\SpecialitiesSeeder::class,
        ]);

    }
}
