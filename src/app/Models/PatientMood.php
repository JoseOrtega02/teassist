<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientMood extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'mood',
        'recorded_at',
    ];

    // Cast recorded_at to a date (Carbon instance)
    protected $casts = [
        'recorded_at' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
