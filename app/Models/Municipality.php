<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $fillable = [
        'name',
        'departament_id'
    ];
    
    public function department()
    {
        return $this->belongsTo(Departament::class);
    }

    public function centers()
    {
        return $this->hasMany(Center::class);
    }


}
