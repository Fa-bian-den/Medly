<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;


    protected $table = 'services';

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Relación many-to-many con centros a través de la tabla pivot center_service.
     */
    public function centers(): BelongsToMany
    {
        return $this->belongsToMany(Center::class, 'center_service')
                    ->withPivot('price', 'active')
                    ->withTimestamps();
    }

    /**
     * Buscar servicio por código
     */
    public static function findByCode(string $code): ?self
    {
        return static::where('code', $code)->first();
    }
}