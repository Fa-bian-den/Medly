<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentDocument extends Model
{
    use HasFactory;

    protected $table = 'appointment_documents';

    protected $fillable = [
        'appointment_id',
        'uploaded_by',  // usuario que subió el archivo
        'type',
        'path',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    // Usuario que subió el documento
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}