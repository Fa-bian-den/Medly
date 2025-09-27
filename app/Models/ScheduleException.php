<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleException extends Model
{
    use HasFactory;

    protected $table = 'schedule_exceptions';

    protected $fillable = [
        'schedule_id',
        'user_id',        // usuario que creó la excepción (médico o admin)
        'center_id',
        'date',
        'start_time',
        'end_time',
        'type',
        'reason',
    ];

    protected $casts = [
        'date'       => 'date',
        'start_time' => 'datetime:H:i', 
        'end_time'   => 'datetime:H:i',
    ];

    // Relaciones
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'center_id');
    }

    // Scopes
    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForSchedule($query, $scheduleId)
    {
        return $query->where('schedule_id', $scheduleId);
    }

    // Busca si existe una excepción que se cruce con el intervalo dado
public static function overlaps($scheduleId, $date, $startTime = null, $endTime = null)
{
    // Base: filas del mismo schedule y día
    $q = self::where('schedule_id', $scheduleId)->where('date', $date);

    // Si no pasaron horas, se interpreta como "día completo".
    // Entonces cualquier excepción ese día ya es conflicto.
    if ($startTime === null || $endTime === null) {
        return $q->exists();
    }
    return $q
        ->whereNotNull('start_time')
        ->whereNotNull('end_time')
        ->whereRaw('? < end_time AND ? > start_time', [$startTime, $endTime])
        ->exists();
}
}