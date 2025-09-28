<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Speciality extends Model
{
    use SoftDeletes;

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function doctorProfiles()
    {
        return $this->belongsToMany(
            \App\Models\DoctorProfile::class,
            'doctor_profile_speciality',
            'speciality_id',
            'doctor_profile_id'
        )->withTimestamps();
    }
}