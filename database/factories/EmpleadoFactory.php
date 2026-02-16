<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empleado>
 */
class EmpleadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'empresa_id' => \App\Models\Empresa::factory(),
            'sede_id' => \App\Models\Sede::factory(),
            'dni_pasaporte' => fake()->bothify('########?'),
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'apellido2' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'fecha_nacimiento' => fake()->date('Y-m-d', '2000-01-01'),
            'cargo' => fake()->jobTitle(),
            'telefono_responsable_laboral' => fake()->phoneNumber(),
            'activo' => true,
        ];
    }
}
