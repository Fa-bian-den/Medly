<?php

namespace App\Policies;

use App\Models\User;

class PatientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('doctor') || $user->hasRole('reception');
    }

    public function view(User $user, User $patient): bool
    {
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('doctor')) {
            // opcional: chequear centro o relación médica (ajusta según tu dominio)
            return true;
        }
        // paciente solo puede ver su propio perfil
        return $user->id === $patient->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('reception');
    }

    public function update(User $user, User $patient): bool
    {
        if ($user->hasRole('admin')) return true;
        // doctor puede editar datos de sus pacientes si está permitido
        if ($user->hasRole('doctor')) return true;
        // paciente puede actualizar su propio perfil
        return $user->id === $patient->id;
    }

    public function delete(User $user, User $patient): bool
    {
        return $user->hasRole('admin');
    }
}