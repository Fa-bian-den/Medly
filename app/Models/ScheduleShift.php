<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleShift extends Model
{
    use HasFactory;

    protected $table = 'schedule_shifts';

    protected $fillable = [
        'schedule_id',
        'start_time',
        'end_time',
        'slot_duration_minutes',
        'capacity',
        'break_minutes',
        'active',
    ];

    protected $casts = [
        'slot_duration_minutes' => 'integer',
        'capacity'              => 'integer',
        'break_minutes'         => 'integer',
        'active'                => 'boolean',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    //encapsula el filtro "active = true" para reutilizarlo en consultas y mejorar legibilidad y mantenimiento.
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

}