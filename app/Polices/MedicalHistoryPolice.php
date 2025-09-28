<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MedicalHistory;

class MedicalHistoryPolicy
{
    public function viewAny(User $user): bool
    {
        // ejemplo: admin o propietario de historiales (ajustar segun roles)
        return $user->hasRole('admin') || $user->hasRole('doctor');
    }

    public function view(User $user, MedicalHistory $medicalHistory): bool
    {
        return $user->hasRole('admin') || $user->id === $medicalHistory->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('doctor') || $user->hasRole('paciente');
    }

    public function update(User $user, MedicalHistory $medicalHistory): bool
    {
        return $user->hasRole('admin') || $user->id === $medicalHistory->user_id;
    }

    public function delete(User $user, MedicalHistory $medicalHistory): bool
    {
        return $user->hasRole('admin');
    }
}