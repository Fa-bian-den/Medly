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

        // permisos y roles (siempre primero)
        $this->call([
            PermissionRoleSeeder::class,
        ]);

        // crear usuario administrador seguro (seeder idempotente debe usar firstOrCreate/updateOrCreate)
        $this->call([
            \Database\Seeders\CreateAdminUserSeeder::class,
        ]);

        //Departamentos y municipios
        $this->call([
            \Database\Seeders\DepartamentSeeder::class,
        ]);
    }
}
