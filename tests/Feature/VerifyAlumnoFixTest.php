<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Alumno;
use App\Models\CursoAcademico;
use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Support\Facades\Schema;

class VerifyAlumnoFixTest extends TestCase
{
    // use RefreshDatabase; // Don't wipe DB if not needed, but safe here. actually let's not wipe to avoid clearing user data if they are using local db. 
    // But `migrate:fresh` already wiped it. So it's fine.
    
    public function test_schema_has_curso_academico_id()
    {
        $this->assertTrue(Schema::hasColumn('alumnos', 'curso_academico_id'));
    }

    public function test_student_email_uniqueness_and_generation()
    {
        // Setup
        $year = CursoAcademico::create(['anyo' => '2099-2100']); // Future year to avoid clash
        $modulo = Modulo::create(['nombre' => 'TEST_MOD']);
        $curso = Curso::create(['nombre' => '1º TEST', 'modulo_id' => $modulo->id]);
        $year->modulos()->attach($modulo->id);

        // 1. Create Student (Email should be generated)
        $response = $this->post('/alumnos', [
            'nombre_completo' => 'Test User',
            'dni' => '99999999A',
            'email' => null, 
            'curso_id' => $curso->id,
            'curso_academico_id' => $year->id,
            // Add other fields if required by validation
            // The validation allows nullable for others
        ]);

        // Check DB
        $this->assertDatabaseHas('alumnos', [
            'dni' => '99999999A',
            'curso_academico_id' => $year->id,
            'email' => 'test.user@alu.medac.es'
        ]);

        // 2. Create Duplicate Student (Email should be unique)
        $response2 = $this->post('/alumnos', [
            'nombre_completo' => 'Test User',
            'dni' => '99999999B', // Diff DNI
            'email' => null,
            'curso_id' => $curso->id,
            'curso_academico_id' => $year->id,
        ]);

        $this->assertDatabaseHas('alumnos', [
            'dni' => '99999999B',
            'email' => 'test.user1@alu.medac.es'
        ]);
    }
}
