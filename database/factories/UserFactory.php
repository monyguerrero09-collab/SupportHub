<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_completo' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'rol_id' => \App\Models\Role::inRandomOrder()->first()?->id ?? \App\Models\Role::firstOrCreate(['nombre' => 'Operador'])->id,
            'codigo_acceso' => 'OP-' . fake()->unique()->numberBetween(1000, 9999),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'activo' => true,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
