<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Therapist;

class PatientTherapistSeeder extends Seeder
{
    public function run(): void
    {
        $therapists = Therapist::all()->pluck('id')->toArray();
        if (empty($therapists)) {
            return; // nothing to attach
        }

        Patient::chunk(50, function ($patients) use ($therapists) {
            foreach ($patients as $patient) {
                // attach 1..3 random therapists for dev/demo
                $count = rand(1, min(3, count($therapists)));
                $toAttach = (array) array_rand(array_flip($therapists), $count);
                $patient->therapists()->sync($toAttach);
            }
        });
    }
}
