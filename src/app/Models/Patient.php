<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'codigo',
        'apellidos',
        'nombres',
        'dni',
        'nacimiento',
        'sexo',
        'telefono',
        'email',
        'direccion',
        'observaciones',
    ];

    /**
     * Relación muchos a muchos con terapeutas (pivot patient_therapist)
     */
    public function therapists(): BelongsToMany
    {
        return $this->belongsToMany(Therapist::class, 'patient_therapist', 'patient_id', 'therapist_id')
                    ->withTimestamps();
    }
}
