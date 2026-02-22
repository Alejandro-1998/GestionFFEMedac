<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;
use App\Models\CursoAcademico;
use App\Models\Modulo;
use App\Models\Curso;

class AlumnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener años académicos
        $anyo2526 = CursoAcademico::where('anyo', '2025-2026')->first();

        // Obtener módulos
        $moduloDAW       = Modulo::where('nombre', 'Desarrollo de Aplicaciones Web')->first();
        $moduloMarketing = Modulo::where('nombre', 'Marketing y Publicidad')->first();
        $moduloAmbiente  = Modulo::where('nombre', 'Guía en el Medio Natural y el Tiempo Libre')->first();

        // Obtener cursos por módulo
        $curso1DAW       = Curso::where('nombre', '1º')->where('modulo_id', $moduloDAW->id)->first();
        $curso1Marketing = Curso::where('nombre', '1º')->where('modulo_id', $moduloMarketing->id)->first();
        $curso2Marketing = Curso::where('nombre', '2º')->where('modulo_id', $moduloMarketing->id)->first();
        $curso1Ambiente  = Curso::where('nombre', '1º')->where('modulo_id', $moduloAmbiente->id)->first();

        // Crear alumnos por curso y año académico
        Alumno::factory()->count(27)->create([
            'curso_id'           => $curso1Marketing->id,
            'curso_academico_id' => $anyo2526->id,
        ]);

        Alumno::factory()->count(30)->create([
            'curso_id'           => $curso2Marketing->id,
            'curso_academico_id' => $anyo2526->id,
        ]);

        Alumno::factory()->count(24)->create([
            'curso_id'           => $curso1DAW->id,
            'curso_academico_id' => $anyo2526->id,
        ]);

        Alumno::factory()->count(32)->create([
            'curso_id'           => $curso1Ambiente->id,
            'curso_academico_id' => $anyo2526->id,
        ]);
    }
}
