<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    /**
     * Solo usuarios con rol admin pueden listar/gestionar administradores.
     * Se asume que el rol 'admin' posee todos los permisos.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, User $admin): bool
    {
        // Admins pueden ver cualquier admin; si quieres, permitir que un admin edite su propio perfil también
        return $user->hasRole('admin') || $user->id === $admin->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, User $admin): bool
    {
        // Admins pueden actualizar cualquier admin; permitir auto-edición también
        return $user->hasRole('admin') || $user->id === $admin->id;
    }

    public function delete(User $user, User $admin): bool
    {
        // Solo admin puede eliminar; proteger contra self-delete si lo deseas (no implementado aquí)
        return $user->hasRole('admin');
    }
}