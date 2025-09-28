<?php

namespace App\Policies;

use App\Models\User;

class DoctorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('reception') || $user->hasRole('doctor');
    }

    public function view(User $user, User $doctor): bool
    {
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('doctor')) {
            return $user->id === $doctor->id;
        }
        // reception puede ver lista y perfiles
        return $user->hasRole('reception');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('reception');
    }

    public function update(User $user, User $doctor): bool
    {
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('doctor')) return $user->id === $doctor->id;
        return false;
    }

    public function delete(User $user, User $doctor): bool
    {
        return $user->hasRole('admin');
    }

    public function review(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('reception');
    }
}