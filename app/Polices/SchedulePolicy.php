<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Schedule;

class SchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('reception') || $user->hasRole('doctor');
    }

    public function view(User $user, Schedule $schedule): bool
    {
        if ($user->hasRole('admin')) return true;
        // Propietario del schedule o personal del mismo centro
        return $schedule->user_id === $user->id || ($user->center_id ?? null) === ($schedule->center_id ?? null);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('reception') || $user->hasRole('doctor');
    }

    public function update(User $user, Schedule $schedule): bool
    {
        if ($user->hasRole('admin')) return true;
        return $schedule->user_id === $user->id;
    }

    public function delete(User $user, Schedule $schedule): bool
    {
        if ($user->hasRole('admin')) return true;
        return $schedule->user_id === $user->id;
    }
}