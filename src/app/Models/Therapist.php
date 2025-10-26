<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * Relación con pacientes (muchos a muchos — pivot patient_therapist)
     */
    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class, 'patient_therapist', 'therapist_id', 'patient_id')
                    ->withTimestamps();
    }
}
