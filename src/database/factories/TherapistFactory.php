<?php

namespace Database\Factories;

use App\Utils\FakeUtils;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Therapist>
 */
class TherapistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake_first = fake()->firstName();
        $fake_last = fake()->lastName();
        $fake_email = FakeUtils::email($fake_first, $fake_last);

        return [
            'apellidos' => $fake_last,
            'nombres' => $fake_first,
            'dni' => $this->faker->unique()->numerify('########'),
            'nacimiento' => $this->faker->date(),
            'sexo' => $this->faker->randomElement(['M', 'F']),
            'telefono' => $this->faker->phoneNumber(),
            'email' => $fake_email,
            'direccion' => $this->faker->address(),
        ];
    }
}