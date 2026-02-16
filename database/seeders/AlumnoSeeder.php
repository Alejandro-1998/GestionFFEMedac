<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;

class AlumnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener un ID de curso aleatorio existente
        $cursoId = \App\Models\CursoAcademico::inRandomOrder()->value('id');

        // Create manual example student
        $notasEjemplo = [8, 9, 7, 8, 9, 10, 6, 8];
        $mediaEjemplo = round(array_sum($notasEjemplo) / count($notasEjemplo), 2);

        Alumno::create([
            'nombre_completo' => 'Alumno Ejemplo',
            'dni' => '12345678Z',
            'curso_academico_id' => $cursoId,
            'nota_1' => $notasEjemplo[0],
            'nota_2' => $notasEjemplo[1],
            'nota_3' => $notasEjemplo[2],
            'nota_4' => $notasEjemplo[3],
            'nota_5' => $notasEjemplo[4],
            'nota_6' => $notasEjemplo[5],
            'nota_7' => $notasEjemplo[6],
            'nota_8' => $notasEjemplo[7],
            'nota_media' => $mediaEjemplo,
        ]);

        for ($i = 0; $i < 10; $i++) {
            $notas = [];
            for ($j = 0; $j < 8; $j++) {
                $notas[] = fake()->numberBetween(1, 10);
            }
            $media = round(array_sum($notas) / count($notas), 2);

            Alumno::create([
                'nombre_completo' => fake()->name(),
                'dni' => fake()->unique()->regexify('[0-9]{8}[A-Z]'),
                'curso_academico_id' => \App\Models\CursoAcademico::inRandomOrder()->value('id'),
                'nota_1' => $notas[0],
                'nota_2' => $notas[1],
                'nota_3' => $notas[2],
                'nota_4' => $notas[3],
                'nota_5' => $notas[4],
                'nota_6' => $notas[5],
                'nota_7' => $notas[6],
                'nota_8' => $notas[7],
                'nota_media' => $media,
            ]);
        }
    }
}
