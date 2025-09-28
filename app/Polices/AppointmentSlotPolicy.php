<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AppointmentSlot;

class AppointmentSlotPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('doctor') || $user->hasRole('reception');
    }

    public function view(User $user, AppointmentSlot $slot): bool
    {
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('doctor')) return $slot->user_id === $user->id;
        if ($user->hasRole('reception')) return $slot->center_id === ($user->center_id ?? null);
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('reception') || $user->hasRole('doctor');
    }

    public function update(User $user, AppointmentSlot $slot): bool
    {
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('doctor')) return $slot->user_id === $user->id;
        if ($user->hasRole('reception')) return $slot->center_id === ($user->center_id ?? null);
        return false;
    }

    public function delete(User $user, AppointmentSlot $slot): bool
    {
        return $user->hasRole('admin') || ($user->hasRole('reception') && $slot->center_id === ($user->center_id ?? null));
    }
}