<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'birthdate',
        'address',
        'idcard',
        'phone',
        'gender',
        'documents_metadata',
        'professional_details',
        'clinical_history',
        'allergies',
        'medications',
        'emergency_contact',
        'clinical_notes',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'documents_metadata'   => 'array',
        'professional_details' => 'array',
        'clinical_history'     => 'array',
        'allergies'            => 'array',
        'medications'          => 'array',
        'emergency_contact'    => 'array',
        'birthdate'            => 'date',
        'deleted_at'           => 'datetime',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Age accessor
    public function getAgeAttribute(): ?int
    {
        return $this->birthdate ? $this->birthdate->age : null;
    }

    // Normaliza teléfono a dígitos
    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = $value === null ? null : preg_replace('/\D+/', '', (string) $value);
    }

    // Añade una entrada al historial clínico y persiste
    public function addClinicalEntry(array $entry): self
    {
        $history = $this->clinical_history ?? [];
        $history[] = $entry;
        $this->clinical_history = $history;
        $this->save();
        return $this;
    }
}