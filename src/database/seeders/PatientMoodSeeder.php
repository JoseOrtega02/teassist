<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Patient;
use App\Models\PatientMood;
use Carbon\Carbon;

class PatientMoodSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $emotions = [
            'Muy triste',
            'Triste',
            'Neutral',
            'Contento',
            'Muy feliz',
        ];

        // Tomar hasta 5 pacientes para mock
        $patients = Patient::limit(5)->get();

        foreach ($patients as $patient) {
            // Crear 10 días de registros mock
            for ($d = 0; $d < 10; $d++) {
                $date = Carbon::today()->subDays($d)->toDateString();
                $mood = $emotions[array_rand($emotions)];

                PatientMood::updateOrCreate(
                    [
                        'patient_id' => $patient->id,
                        'recorded_at' => $date,
                    ],
                    [
                        'mood' => $mood,
                    ]
                );
            }
        }
    }
}
