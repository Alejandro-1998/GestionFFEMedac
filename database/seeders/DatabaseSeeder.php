<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CursoAcademico;
use App\Models\Modulo;
use App\Models\Curso;
use App\Models\Alumno;
use App\Models\Empresa;
use App\Models\Sede;
use App\Models\Empleado;
use App\Models\Convenio;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // 1. Create Users
        $admin = User::factory()->create([
            'nombre' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'rol' => 'admin',
        ]);

        $profesor = User::factory()->create([
            'nombre' => 'Profesor',
            'email' => 'profesor@profesor.com',
            'password' => Hash::make('12345678'),
            'rol' => 'profesor',
        ]);

        // 2. Create Academic Years (Cursos Academicos)
        $year2425 = CursoAcademico::create(['anyo' => '2024-2025']);
        $year2526 = CursoAcademico::create(['anyo' => '2025-2026']);

        // 3. Create Modules (Titulaciones) for 2024-2025
        $moduloDAW = Modulo::create([
            'nombre' => 'Desarrollo de Aplicaciones Web',
        ]);
        $year2425->modulos()->attach($moduloDAW->id);

        $moduloMarketing = Modulo::create([
            'nombre' => 'Marketing y Publicidad',
        ]);
        $year2425->modulos()->attach($moduloMarketing->id);

        // 4. Create Cursos (Groups: 1º, 2º) for Modules
        $curso1DAW = Curso::create(['nombre' => '1º', 'modulo_id' => $moduloDAW->id]);
        $curso2DAW = Curso::create(['nombre' => '2º', 'modulo_id' => $moduloDAW->id]);
        
        $curso1Marketing = Curso::create(['nombre' => '1º', 'modulo_id' => $moduloMarketing->id]);
        // Marketing only has 1st year for now in this example

        // 5. Create Students (Alumnos) assigned to Cursos
        Alumno::factory()->count(10)->create([
            'curso_id' => $curso2DAW->id, // Assign to 2º DAW
            'curso_academico_id' => $year2425->id,
        ]);

        Alumno::factory()->count(5)->create([
            'curso_id' => $curso1DAW->id, // Assign to 1º DAW
            'curso_academico_id' => $year2425->id,
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
        $empresa1->modulos()->attach($moduloDAW->id);
        
        // Marketing Creativo works with Marketing
        $empresa2->modulos()->attach($moduloMarketing->id);


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
        
        // 9. Create example Convenio (optional, requiring a student from 2º DAW)
        $student = Alumno::where('curso_id', $curso2DAW->id)->first();
        if ($student) {
             Convenio::create([
                'alumno_id' => $student->id,
                'profesor_id' => $profesor->id,
                'empresa_id' => $empresa1->id,
                'empleado_id' => $empleado1->id, // Tutor laboral
                'curso_academico_id' => $year2425->id, // Keep this for easier checking? Or link via cycle
                // Note: Convenio model might still link to year directly or via student->curso->modulo->year. 
                // Ensuring Convenio migration wasn't dropped, just need to make sure IDs exist.
                'sede_id' => $sede1->id,
                'total_horas' => 370,
                'fecha_inicio' => Carbon::now()->addDays(1),
                'fecha_fin' => Carbon::now()->addMonths(3),
                'estado' => 'en_proceso',
            ]);
        }
    }
}