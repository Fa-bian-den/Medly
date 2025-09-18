<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Limpiar caché de permisos del paquete
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name'  => 'System',
                'password'   => Hash::make('Hackatamal'),
                'status'     => 'active',
                'email_verified_at' => now(),
            ]
        );

                User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'System',
                'password' => Hash::make('Hackatamal'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $role = Role::firstOrCreate(['name' => 'admin']);
        $admin->assignRole($role);
    }
}
