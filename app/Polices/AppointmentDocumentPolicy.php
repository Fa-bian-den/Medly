<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AppointmentDocument;

class AppointmentDocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('doctor') || $user->hasRole('reception');
    }

    public function view(User $user, AppointmentDocument $document): bool
    {
        if ($user->hasRole('admin')) return true;
        // doctor responsable de la cita puede ver
        if ($user->hasRole('doctor')) return $document->appointment->user_id === $user->id;
        // quien subió o paciente asociado puede ver
        if ($user->hasRole('paciente')) return $document->appointment->patient_id === $user->id;
        return $document->uploaded_by === $user->id;
    }

    public function create(User $user): bool
    {
        // admin, doctor, reception y paciente pueden subir (según flujo)
        return $user->hasRole('admin') || $user->hasRole('doctor') || $user->hasRole('reception') || $user->hasRole('paciente');
    }

    public function delete(User $user, AppointmentDocument $document): bool
    {
        // admin o quien subió el documento
        if ($user->hasRole('admin')) return true;
        return $document->uploaded_by === $user->id;
    }
}