<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Appointment;

class AppointmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('doctor') || $user->hasRole('paciente');
    }

    public function view(User $user, Appointment $appointment): bool
    {
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('doctor')) return $appointment->user_id === $user->id;
        if ($user->hasRole('paciente')) return $appointment->patient_id === $user->id;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('doctor') || $user->hasRole('paciente');
    }

    public function update(User $user, Appointment $appointment): bool
    {
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('doctor')) return $appointment->user_id === $user->id;
        return false;
    }

    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->hasRole('admin');
    }
}