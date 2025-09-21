<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CenterUser extends Pivot
{
    protected $table = 'center_user';
    protected $fillable = ['user_id','center_id','active_from','active_to','primary','notes'];
    public $timestamps = true;

    public function user() { return $this->belongsTo(\App\Models\User::class); }
    public function center() { return $this->belongsTo(\App\Models\Center::class); }

}
