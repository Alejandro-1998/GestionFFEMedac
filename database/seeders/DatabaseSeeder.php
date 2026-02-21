<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Models\Curso;
use App\Models\Alumno;
use App\Models\Empresa;
use App\Models\Sede;
use App\Models\Empleado;
use App\Models\Convenio;
use App\Models\CursoAcademico;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(CursoAcademicoSeeder::class);

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

        // 6. Create Companies (Empresas)
        $empresa1 = Empresa::create([
            'nombre' => 'Tech Solutions S.L.',
            'email' => 'contact@techsolutions.com',
            'telefono' => '912345678',
            'direccion' => 'Calle Tecnológica 123',
            'nif' => 'B12345678',
        ]);

        $empresa2 = Empresa::create([
            'nombre' => 'Marketing Creativo S.A.',
            'email' => 'info@marketingcreativo.com',
            'telefono' => '934567890',
            'direccion' => 'Avenida Creatividad 45',
            'nif' => 'A98765432',
        ]);

        // 7. Assign Companies to Modules
        // Tech Solutions works with DAW
        // $empresa1->modulos()->attach($moduloDAW->id);
        
        // // Marketing Creativo works with Marketing
        // $empresa2->modulos()->attach($moduloMarketing->id);


        // 8. Create Sedes and Empleados (Tutors)
        $sede1 = Sede::create([
            'empresa_id' => $empresa1->id,
            'nombre' => 'Sede Principal',
            'ubicacion' => 'Madrid',
            'direccion' => 'Calle Tecnológica 123 - Principal',
            'telefono' => '910000000',
        ]);

        $empleado1 = Empleado::create([
            'empresa_id' => $empresa1->id,
            'sede_id' => $sede1->id,
            'dni_pasaporte' => '12345678A',
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'apellido2' => 'Gómez',
            'email' => 'juan.perez@techsolutions.com',
            'fecha_nacimiento' => '1990-01-01',
            'telefono_responsable_laboral' => '600123456',
            'cargo' => 'Senior Developer',
            'activo' => true,
        ]);
        
        // // 9. Create example Convenio (optional, requiring a student from 2º DAW)
        // $student = Alumno::where('curso_id', $curso2DAW->id)->first();
        // if ($student) {
        //      Convenio::create([
        //         'alumno_id' => $student->id,
        //         'profesor_id' => $profesor->id,
        //         'empresa_id' => $empresa1->id,
        //         'empleado_id' => $empleado1->id, // Tutor laboral
        //         'curso_academico_id' => $year2425->id, // Keep this for easier checking? Or link via cycle
        //         // Note: Convenio model might still link to year directly or via student->curso->modulo->year. 
        //         // Ensuring Convenio migration wasn't dropped, just need to make sure IDs exist.
        //         'sede_id' => $sede1->id,
        //         'total_horas' => 370,
        //         'fecha_inicio' => Carbon::now()->addDays(1),
        //         'fecha_fin' => Carbon::now()->addMonths(3),
        //         'estado' => 'en_proceso',
        //     ]);
        // }
    }
}