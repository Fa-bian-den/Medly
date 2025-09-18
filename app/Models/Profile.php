<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birthdate',
        'address',
        'phone',
        'gender',
        'documents_metadata',
        'professional_details'
    ];

    protected $casts = [
        'documents_metadata' => 'array',
        'professional_details' => 'array',
        'birthdate' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
