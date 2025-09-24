<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar caché de permisos del paquete
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $perms = [
            'users.create','users.view.own','users.update.own',
            'doctor.apply','doctor.view.pending','doctor.verify','doctor.manage.schedule',
            'appointments.create','appointments.view.own','appointments.manage',
            'registro.view.own','registro.view.hospital','queue.manage',
            'hospitals.manage','reports.generate'
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        Role::firstOrCreate(['name' => 'admin'])->givePermissionTo(Permission::all());

        Role::firstOrCreate(['name' => 'doctor'])->givePermissionTo([
            'doctor.apply','doctor.manage.schedule','appointments.manage',
            'registro.view.hospital','queue.manage','reports.generate'
        ]);

        Role::firstOrCreate(['name' => 'paciente'])->givePermissionTo([
            'users.create','users.view.own','users.update.own',
            'appointments.create','appointments.view.own','registro.view.own'
        ]);

    }
}
