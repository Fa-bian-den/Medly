<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'doctor_id',
        'center_id',
        'name',
        'recurrence_type',
        'recurrence_days',
        'start_date',
        'end_date',
        'active',
    ];

    protected $casts = [
        'recurrence_days' => 'array',
        'start_date'      => 'date',
        'end_date'        => 'date',
        'active'          => 'boolean',
    ];

    public const RECURRENCE_NONE = 'none';
    public const RECURRENCE_DAILY = 'daily';
    public const RECURRENCE_WEEKLY = 'weekly';
    public const RECURRENCE_MONTHLY = 'monthly';

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'center_id');
    }

    public function shifts()
    {
        return $this->hasMany(ScheduleShift::class, 'schedule_id');
    }

    public function exceptions()
    {
        return $this->hasMany(ScheduleException::class, 'schedule_id');
    }
}