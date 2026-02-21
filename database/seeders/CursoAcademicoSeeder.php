<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CursoAcademico;
use App\Models\Modulo;
use App\Models\Curso;

class CursoAcademicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cursos Académicos
        $anyo2425 = CursoAcademico::create(['anyo' => '2024-2025']);
        $anyo2526 = CursoAcademico::create(['anyo' => '2025-2026', 'actual' => true]);

        // Módulos
        $moduloDAW = Modulo::create([
            'nombre' => 'Desarrollo de Aplicaciones Web',
        ]);

        $moduloMarketing = Modulo::create([
            'nombre' => 'Marketing y Publicidad',
        ]);

        $moduloAmbiente = Modulo::create([
            'nombre' => 'Guía en el Medio Natural y el Tiempo Libre',
        ]);

        $moduloEnsenyanza = Modulo::create([
            'nombre' => 'Enseñanza y Animación  Sociodeportiva',
        ]);

        $moduloAcondicionamiento = Modulo::create([
            'nombre' => 'Acondicionamiento Físico',
        ]);

        $anyo2526->modulos()->attach($moduloDAW->id);
        $anyo2526->modulos()->attach($moduloMarketing->id);
        $anyo2526->modulos()->attach($moduloAmbiente->id);
        $anyo2526->modulos()->attach($moduloEnsenyanza->id);
        $anyo2526->modulos()->attach($moduloAcondicionamiento->id);

        // Cursos
        $curso1DAW = Curso::create(['nombre' => '1º', 'modulo_id' => $moduloDAW->id]);
        $curso2DAW = Curso::create(['nombre' => '2º', 'modulo_id' => $moduloDAW->id]);
        $curso1Marketing = Curso::create(['nombre' => '1º', 'modulo_id' => $moduloMarketing->id]);
        $curso2Marketing = Curso::create(['nombre' => '2º', 'modulo_id' => $moduloMarketing->id]);
        $curso1Ambiente = Curso::create(['nombre' => '1º', 'modulo_id' => $moduloAmbiente->id]);
    }
}
