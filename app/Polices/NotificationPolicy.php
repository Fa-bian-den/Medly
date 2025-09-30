<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;

class NotificationPolicy
{
    public function viewAny(User $user): bool
    {
        // Users can view their own notifications; admin can view all
        return true;
    }

    public function view(User $user, Notification $notification): bool
    {
        if ($user->hasRole('admin')) return true;
        return $notification->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        // Admins or system roles can create notifications; reception/doctors typically not
        return $user->hasRole('admin');
    }

    public function update(User $user, Notification $notification): bool
    {
        // Mark read/unread allowed for owner or admin
        if ($user->hasRole('admin')) return true;
        return $notification->user_id === $user->id;
    }

    public function delete(User $user, Notification $notification): bool
    {
        // Deleting notification reserved to admin or owner
        if ($user->hasRole('admin')) return true;
        return $notification->user_id === $user->id;
    }
}