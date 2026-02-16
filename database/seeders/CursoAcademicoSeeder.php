<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CursoAcademico;

class CursoAcademicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ciclos = [
            'DAW' => ['1º DAW', '2º DAW'],
            'Marketing' => ['1º Marketing', '2º Marketing'],
            'TEGI' => ['1º TEGI', '2º TEGI'],
        ];

        foreach ($ciclos as $nombreCiclo => $cursos) {
            $ciclo = \App\Models\Ciclo::firstOrCreate(['nombre' => $nombreCiclo]);
            
            foreach ($cursos as $curso) {
                CursoAcademico::firstOrCreate(
                    ['anyo' => $curso],
                    ['ciclo_id' => $ciclo->id]
                );
            }
        }
    }
}
