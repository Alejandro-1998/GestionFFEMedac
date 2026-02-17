<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Curso;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alumno>
 */
class AlumnoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_completo' => $this->faker->name(),
            'dni' => $this->faker->unique()->regexify('[0-9]{8}[A-Z]{1}'),
            'email' => $this->faker->unique()->userName() . '@alu.medac.es',
            // 'telefono' => $this->faker->phoneNumber(), // Removed as column doesn't exist
            // 'email' => $this->faker->unique()->safeEmail(), // Removed as column doesn't exist
            // 'direccion' => $this->faker->address(), // Removed as column doesn't exist
            'curso_id' => Curso::factory(), // Default to creating a course if not provided
            'curso_academico_id' => \App\Models\CursoAcademico::factory(), 
            'nota_media' => $this->faker->randomFloat(2, 5, 10),
            'nota_1' => $this->faker->randomFloat(2, 5, 10),
            'nota_2' => $this->faker->randomFloat(2, 5, 10),
            'nota_3' => $this->faker->randomFloat(2, 5, 10),
            'nota_4' => $this->faker->randomFloat(2, 5, 10),
            'nota_5' => $this->faker->randomFloat(2, 5, 10),
            'nota_6' => $this->faker->randomFloat(2, 5, 10),
            'nota_7' => $this->faker->randomFloat(2, 5, 10),
            'nota_8' => $this->faker->randomFloat(2, 5, 10),
        ];
    }
}
