<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentSlot extends Model
{
    use HasFactory;

    protected $table = 'appointment_slots';

    protected $fillable = [
        'center_id',
        'user_id',        // user_id representa el médico responsable del slot
        'service_id',
        'date',
        'start_time',
        'end_time',
        'capacity',
    ];

    protected $casts = [
        'date'     => 'date',
        'capacity' => 'integer',
    ];

    // Relaciones
    public function center()
    {
        return $this->belongsTo(Center::class, 'center_id');
    }

    // Este user debe ser un médico según la lógica de negocio
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // Scopes útiles
    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('user_id', $doctorId);
    }

    // Capacidad disponible
    public function availableCapacity(int $bookedCount): int
    {
        return max(0, $this->capacity - $bookedCount);
    }
}