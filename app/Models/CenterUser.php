<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\Center;

class CenterUser extends Pivot
{
    protected $table = 'center_user';

    // Si tu tabla tiene id auto-increment, dejar $incrementing en true.
    public $timestamps = true;

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'user_id',
        'center_id',
        'active_from',
        'active_to',
        'is_primary',
        'notes',
    ];

    // Casts para trabajar con tipos correctos en el código
    protected $casts = [
        'active_from' => 'datetime',
        'active_to'   => 'datetime',
        'is_primary'  => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'center_id');
    }

    // Accesor para compatibilidad si alguna parte del código usa $pivot->primary
    public function getPrimaryAttribute()
    {
        return (bool) $this->is_primary;
    }

    // Mutator opcional para facilitar asignación desde booleano o string
    public function setIsPrimaryAttribute($value): void
    {
        $this->attributes['is_primary'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}