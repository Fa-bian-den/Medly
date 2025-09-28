<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'status',
        'carnet_minsa',
        'password',
        'provider',
        'provider_id',
        'avatar',
    ];

    // Campos ocultos al serializar
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts para fechas y password hashed
    protected $casts = [
        'email_verified_at' => 'datetime',
        'deleted_at'        => 'datetime',
        'password'          => 'hashed',
    ];

    // Nombre completo accesible como $user->name
    // (usa trim para evitar espacios extra)
    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    //
    // Relaciones directas y comunes
    //


    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function medicalHistory()
{
    return $this->hasOne(\App\Models\MedicalHistory::class, 'user_id');
}

    // Códigos de verificación por email (1:N)
    public function emailVerificationCodes()
    {
        return $this->hasMany(EmailVerificationCode::class, 'user_id');
    }

    // Entrada en la tabla pivot center_user (1:N)
    public function centerUsers()
    {
        return $this->hasMany(CenterUser::class, 'user_id');
    }

    // Centros a los que participa vía pivot center_user (N:N)
    public function centers()
    {
        // especifico FK y otras columnas del pivot para evitar supuestos
        return $this->belongsToMany(Center::class, 'center_user', 'user_id', 'center_id')
                    ->withPivot(['active_from', 'active_to', 'is_primary', 'notes'])
                    ->withTimestamps();
    }

    // Notificaciones recibidas (1:N)
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    //
    // Relaciones relacionadas con roles clínicos, horarios y calendarización
    //

    // Perfil médico (doctor_profiles) si aplica (1:1)
    public function doctorProfile()
    {
        return $this->hasOne(DoctorProfile::class, 'user_id');
    }

    // Schedules donde este user es doctor (1:N)
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'doctor_id');
    }

    // ScheduleShifts a través de schedules (hasManyThrough)
    public function scheduleShifts()
    {
        return $this->hasManyThrough(
            ScheduleShift::class,
            Schedule::class,
            'doctor_id',   // FK en schedules que apunta a users.id
            'schedule_id', // FK en schedule_shifts que apunta a schedules.id
            'id',
            'id'
        );
    }

    // ScheduleExceptions a través de schedules (hasManyThrough)
    public function scheduleExceptions()
    {
        return $this->hasManyThrough(
            ScheduleException::class,
            Schedule::class,
            'doctor_id',
            'schedule_id',
            'id',
            'id'
        );
    }

    //
    // Relaciones con citas, slots y documentos
    //

    // Citas donde es paciente (1:N)
    public function appointmentsAsPatient()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    // Citas donde es médico responsable (1:N)
    public function appointmentsAsDoctor()
    {
        return $this->hasMany(Appointment::class, 'user_id');
    }

    // Citas que creó (auditoría) (1:N)
    public function createdAppointments()
    {
        return $this->hasMany(Appointment::class, 'created_by');
    }

    // Citas que canceló (auditoría) (1:N)
    public function cancelledAppointments()
    {
        return $this->hasMany(Appointment::class, 'cancelled_by');
    }

    // Slots creados por el doctor (1:N)
    public function appointmentSlots()
    {
        return $this->hasMany(AppointmentSlot::class, 'user_id');
    }

    // Documentos subidos por el usuario (1:N)
    public function uploads()
    {
        return $this->hasMany(AppointmentDocument::class, 'uploaded_by');
    }

    //
    // Scopes y helpers básicos
    //

    // Scope para usuarios activos (asume columna status)
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Helper corto para comprobar si el usuario tiene perfil médico
    public function isDoctor(): bool
    {
        return $this->hasRole('doctor') || $this->doctorProfile()->exists();
    }
}