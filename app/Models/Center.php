<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Municipality;
use App\Models\User;
use App\Models\CenterUser;
use App\Models\CenterService;
use App\Models\AppointmentSlot;
use App\Models\Appointment;

class Center extends Model
{
    use HasFactory;

    // Campos asignables
    protected $fillable = [
        'municipality_id',
        'name',
        'logo',
        'address',
        'url',
        'phone',
        'ruc',
        'is_public',
    ];


    protected $casts = [
        'is_public' => 'boolean',
        'municipality_id' => 'integer',
    ];

    // Relación con municipio
    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }

    // Usuarios relacionados vía pivot center_user
    public function users()
    {
        return $this->belongsToMany(User::class, 'center_user', 'center_id', 'user_id')
                    ->withPivot(['active_from', 'active_to', 'is_primary', 'notes'])
                    ->withTimestamps();
    }

    // Acceso directo a filas pivot
    public function centerUsers()
    {
        return $this->hasMany(CenterUser::class, 'center_id');
    }

    // Servicios ofrecidos en este centro
    public function centerServices()
    {
        return $this->hasMany(CenterService::class, 'center_id');
    }

    // Slots pertenecientes al centro
    public function appointmentSlots()
    {
        return $this->hasMany(AppointmentSlot::class, 'center_id');
    }

    // Citas realizadas en el centro
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'center_id');
    }

    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = $value === null ? null : preg_replace('/\D+/', '', (string) $value);
    }

    // Scope para buscar por municipio
    public function scopeForMunicipality($query, int $municipalityId)
    {
        return $query->where('municipality_id', $municipalityId);
    }
}