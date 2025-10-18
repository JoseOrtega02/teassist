<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Therapist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'apellidos',
        'nombres',
        'dni',
        'nacimiento',
        'sexo',
        'telefono',
        'email',
        'direccion',
    ];

    /**
     * Relación con el usuario (un terapeuta pertenece a un usuario)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con pacientes (un terapeuta tiene muchos pacientes)
     */
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'therapist_id');
    }
}
