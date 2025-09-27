<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $email = 'admin@example.com';
        $password = 'Hackatamal'; // cambia en producción

        // Crea o actualiza el admin
        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'first_name'        => 'Admin',
                'last_name'         => 'System',
                'password'          => Hash::make($password),
                'status'            => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Asegura rol admin con guard_name consistente y asigna si hace falta
        $role = Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);
        if (! $admin->hasRole('admin')) {
            $admin->assignRole($role);
        }
    }
}