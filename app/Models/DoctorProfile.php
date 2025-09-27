<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorProfile extends Model
{
    use HasFactory;

    // Tabla asociada (opcional si sigue convención)
    protected $table = 'doctor_profiles';

    // Campos asignables
    protected $fillable = [
        'user_id',
        'center_id_proposed',
        'carnet_minsa',
        'documents',
        'ruc',
        'specialties',
        'status_validation',
        'reviewed_by',
        'validated_at',
        'comments',
    ];

    // Casts para JSON y fechas
    protected $casts = [
        'documents'       => 'array',
        'specialties'     => 'array',
        'validated_at'    => 'datetime',
    ];

    // Constantes de estados para evitar strings mágicos
    public const STATUS_PENDIENTE   = 'pendiente';
    public const STATUS_EN_REVISION = 'en_revision';
    public const STATUS_APROBADO    = 'aprobado';
    public const STATUS_RECHAZADO   = 'rechazado';

    /**
     * Relaciones
     */

    // Perfil pertenece a un User (médico)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Centro propuesto por el médico (opcional)
    public function proposedCenter(): BelongsTo
    {
        return $this->belongsTo(Center::class, 'center_id_proposed');
    }

    // Usuario que revisó (admin/revisor)
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scopes para facilitar consultas por estado
     */
    public function scopePendiente($query)
    {
        return $query->where('status_validation', self::STATUS_PENDIENTE);
    }

    public function scopeEnRevision($query)
    {
        return $query->where('status_validation', self::STATUS_EN_REVISION);
    }

    public function scopeAprobado($query)
    {
        return $query->where('status_validation', self::STATUS_APROBADO);
    }

    public function scopeRechazado($query)
    {
        return $query->where('status_validation', self::STATUS_RECHAZADO);
    }

    /**
     * Helpers de estado
     */
    public function isAprobado(): bool
    {
        return $this->status_validation === self::STATUS_APROBADO;
    }

    public function isPendiente(): bool
    {
        return $this->status_validation === self::STATUS_PENDIENTE;
    }
}