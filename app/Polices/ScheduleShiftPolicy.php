<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ScheduleShift;

class ScheduleShiftPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('reception') || $user->hasRole('doctor');
    }

    public function view(User $user, ScheduleShift $shift): bool
    {
        if ($user->hasRole('admin')) return true;
        return $shift->schedule->user_id === $user->id || ($user->center_id ?? null) === ($shift->schedule->center_id ?? null);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('reception') || $user->hasRole('doctor');
    }

    public function update(User $user, ScheduleShift $shift): bool
    {
        if ($user->hasRole('admin')) return true;
        return $shift->schedule->user_id === $user->id;
    }

    public function delete(User $user, ScheduleShift $shift): bool
    {
        if ($user->hasRole('admin')) return true;
        return $shift->schedule->user_id === $user->id;
    }
}