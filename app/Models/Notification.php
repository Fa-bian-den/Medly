<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',    // destinatario de la notificación
        'type',
        'title',
        'body',
        'data',
        'read_at',
        'sent_at',
    ];

    protected $casts = [
        'data'    => 'array',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    // Relación con el usuario destinatario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope para no leídos
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    // Scope para un usuario
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Marcar como leído
    public function markAsRead(): bool
    {
        $this->read_at = now();
        return $this->save();
    }
}