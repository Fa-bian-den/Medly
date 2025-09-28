<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ScheduleException;

class ScheduleExceptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('reception') || $user->hasRole('doctor');
    }

    public function view(User $user, ScheduleException $exception): bool
    {
        if ($user->hasRole('admin')) return true;
        return $exception->schedule->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('reception') || $user->hasRole('doctor');
    }

    public function update(User $user, ScheduleException $exception): bool
    {
        if ($user->hasRole('admin')) return true;
        return $exception->schedule->user_id === $user->id;
    }

    public function delete(User $user, ScheduleException $exception): bool
    {
        if ($user->hasRole('admin')) return true;
        return $exception->schedule->user_id === $user->id;
    }
}