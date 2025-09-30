<?php

namespace App\Policies;

use App\Models\Center;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CenterPolicy
{
    use HandlesAuthorization;

    // Super-admin shortcut si lo necesitas (ajusta a tu rol)
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        
    }

    public function viewAny(User $user): bool
    {
        return $user->can('centers.view') ?? true;
    }

    public function view(User $user, Center $center): bool
    {
        return $user->can('centers.view') ?? true;
    }

    public function create(User $user): bool
    {
        return $user->can('centers.create');
    }

    public function update(User $user, Center $center): bool
    {
        return $user->can('centers.update');
    }

    public function delete(User $user, Center $center): bool
    {
        return $user->can('centers.delete');
    }
}