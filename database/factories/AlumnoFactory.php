<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Curso;
use App\Models\CursoAcademico;

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
        $nota1 = $this->faker->randomFloat(2, 5, 10);
        $nota2 = $this->faker->randomFloat(2, 5, 10);
        $nota3 = $this->faker->randomFloat(2, 5, 10);
        $nota4 = $this->faker->randomFloat(2, 5, 10);
        $nota5 = $this->faker->randomFloat(2, 5, 10);
        $nota6 = $this->faker->randomFloat(2, 5, 10);
        $nota7 = $this->faker->randomFloat(2, 5, 10);
        $nota8 = $this->faker->randomFloat(2, 5, 10);
        $notaMedia = ($nota1 + $nota2 + $nota3 + $nota4 + $nota5 + $nota6 + $nota7 + $nota8) / 8;

        return [
            'nombre_completo' => $this->faker->name(),
            'dni' => $this->faker->unique()->regexify('[0-9]{8}[A-Z]{1}'),
            'email' => $this->faker->unique()->userName() . '@alu.medac.es',
            'nota_1' => $nota1,
            'nota_2' => $nota2,
            'nota_3' => $nota3,
            'nota_4' => $nota4,
            'nota_5' => $nota5,
            'nota_6' => $nota6,
            'nota_7' => $nota7,
            'nota_8' => $nota8,
            'nota_media' => $notaMedia,
        ];
    }
}
