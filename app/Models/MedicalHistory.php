<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalHistory extends Model
{
    use SoftDeletes;

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'user_id',
        'recorded_by',
        'summary',
        'details',
        'allergies',
        'blood_type',
        'notes',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    // Casts para trabajar con json y fechas como arrays/objetos
    protected $casts = [
        'details' => 'array',
        'allergies' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación 1:1 hacia User (paciente)
    public function user()
    {
        // usuario dueño del historial
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación hacia quien registró/actualizó el historial
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}