<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

// Models y Policies que registraremos
use App\Models\MedicalHistory;
use App\Policies\MedicalHistoryPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Mapa de modelos a policies.
     *
     * Añade aquí cada pair Model => Policy que crees.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Registrar la policy de MedicalHistory
        MedicalHistory::class => MedicalHistoryPolicy::class,

        \App\Models\Appointment::class => \App\Policies\AppointmentPolicy::class,

        \App\Models\AppointmentSlot::class => \App\Policies\AppointmentSlotPolicy::class,

        \App\Models\AppointmentDocument::class => \App\Policies\AppointmentDocumentPolicy::class,

        \App\Models\Schedule::class => \App\Policies\SchedulePolicy::class,

        \App\Models\ScheduleShift::class => \App\Policies\ScheduleShiftPolicy::class,

        \App\Models\ScheduleException::class => \App\Policies\ScheduleExceptionPolicy::class,

        \App\Models\User::class => \App\Policies\PatientPolicy::class,

        \App\Models\User::class => \App\Policies\DoctorPolicy::class,

    ];

    /**
     * Registra las policies y define gates adicionales.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gates centrales de autorización
        Gate::define('manage-users', fn($user) => method_exists($user, 'hasRole') ? $user->hasRole('admin') : false);

        // Ejemplo: quienes pueden ver reportes (admin o doctor)
        Gate::define('view-reports', fn($user) => method_exists($user, 'hasRole') ? ($user->hasRole('admin') || $user->hasRole('doctor')) : false);

        // Ejemplo: solo el propio usuario o admin puede gestionar su cuenta
        Gate::define('manage-own-account', function ($user, $targetUser) {
            if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                return true;
            }
            return $user->id === ($targetUser->id ?? $targetUser);
        });
    }
}