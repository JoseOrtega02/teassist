<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        fake()->seed(10);
        $this->call(PermissionsSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(PatientSeeder::class);
        $this->call(ActivitySeeder::class);
        $this->call(PatientActivitySeeder::class);
        $this->call(TherapistSeeder::class);
        $this->call(PatientTherapistSeeder::class);
        $this->call(PatientMoodSeeder::class);
    }
}
