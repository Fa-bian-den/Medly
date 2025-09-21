<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    protected $fillable = [
    'name',
    'address',
    'latitude',
    'longitude',
    'phone',
    'municipality_id'

    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'center_user')
                    ->withPivot(['active_from','active_to','primary','notes'])
                    ->withTimestamps();
    }

    public function centerUsers()
    {
        return $this->hasMany(CenterUser::class);
    }



}
