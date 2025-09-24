<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'status',
        'carnet_minsa',
        'password',
        'provider',
        'provider_id',
        'avatar',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helper para nombre completo
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Relaciones
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function centers()
    {
        return $this->belongsToMany(\App\Models\Center::class, 'center_user')
                    ->withPivot(['active_from','active_to','primary','notes'])
                    ->withTimestamps();
    }

    public function centerUsers()
    {
        return $this->hasMany(\App\Models\CenterUser::class);
    }


}
