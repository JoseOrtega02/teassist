<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ActivitySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Activity::factory()->count(3)->create();

        // Ensure a mood-selection activity exists for therapists to assign
        Activity::firstOrCreate(
            ['name' => 'Seleccionar estado de ánimo'],
            ['description' => 'Actividad para que el paciente registre su estado de ánimo del día.', 'image' => null]
        );
    }
}
