<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = [
        'patient_id',
        'user_id',          // médico responsable
        'center_id',
        'service_id',
        'slot_id',
        'scheduled_at',
        'status',
        'reason',
        'created_by',
        'cancelled_by',
        'cancellation_reason',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    // Estados como constantes para evitar strings repetidos
    public const STATUS_PENDING     = 'pending';
    public const STATUS_CONFIRMED   = 'confirmed';
    public const STATUS_RESCHEDULED = 'rescheduled';
    public const STATUS_ATTENDED    = 'attended';
    public const STATUS_CANCELLED   = 'cancelled';
    public const STATUS_NO_SHOW     = 'no_show';

    // Relaciones

    // paciente (user con rol paciente)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // médico responsable (user con rol médico)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'center_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // slot asociado (nullable)
    public function slot()
    {
        return $this->belongsTo(AppointmentSlot::class, 'slot_id');
    }

    // auditoría: quien creó la cita
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // auditoría: quien canceló la cita
    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    // Scopes útiles
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('user_id', $doctorId);
    }

    public function scopeBetween($query, $from, $to)
    {
        return $query->whereBetween('scheduled_at', [$from, $to]);
    }

    // Helper corto para marcar cancelada la cita
    public function markCancelled(?int $byUserId = null, ?string $reason = null): bool
    {
        $this->status = self::STATUS_CANCELLED;
        $this->cancelled_by = $byUserId;
        $this->cancellation_reason = $reason;
        return $this->save(); // save devuelve true/false
    }

    public function markAttended(): bool
    {
        $this->status = self::STATUS_ATTENDED;
        return $this->save();
    }

    public function isCancelable(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_RESCHEDULED,
        ]);
    }
}