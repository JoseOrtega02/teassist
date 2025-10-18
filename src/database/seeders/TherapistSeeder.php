<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Therapist;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;

class TherapistSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory()
            ->count(10)
            ->create();

        foreach ($users as $user) {
            // Asignar rol therapist
            $user->assignRole('therapist');
            $user->role = 'therapist';
            $user->save();

            // Crear terapeuta
            $therapist = Therapist::create([
                'user_id' => $user->id,
                'nombres' => explode(' ', $user->name)[0],
                'apellidos' => explode(' ', $user->name)[1] ?? 'Apellido',
                'dni' => fake()->unique()->numerify('########'),
                'nacimiento' => fake()->date(),
                'sexo' => fake()->randomElement(['M', 'F']),
                'telefono' => fake()->phoneNumber(),
                'email' => $user->email,
                'direccion' => fake()->address(),
            ]);
        }
    }
}