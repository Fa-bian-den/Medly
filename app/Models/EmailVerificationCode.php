<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmailVerificationCode extends Model
{
    protected $table = 'email_verification_codes';

    // Asignables
    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
        'used',
        'attempts',
    ];

    // Casts más completos
    protected $casts = [
        'expires_at' => 'datetime',
        'used'       => 'boolean',
        'attempts'   => 'integer',
    ];

    // Relación con el usuario (clave explícita para claridad)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Devuelve true si el código ya expiró
    public function isExpired(): bool
    {
        return $this->expires_at ? $this->expires_at->isPast() : false;
    }

    // Scope: códigos no usados
    public function scopeUnused($query)
    {
        return $query->where('used', false);
    }

    // Scope: códigos válidos (no usados y no expirados)
    public function scopeValid($query)
    {
        return $query->where('used', false)->where(function ($q) 
        {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        });
    }

    // Marcar como usado y persistir
    public function markUsed(): bool
    {
        $this->used = true;
        return $this->save();
    }

    // Incrementar contador de intentos y persistir
    public function incrementAttempts(int $by = 1): int
    {
        $this->attempts = ($this->attempts ?? 0) + $by;
        $this->save();
        return $this->attempts;
    }
}