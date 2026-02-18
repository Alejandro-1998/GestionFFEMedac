<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Curso;
use App\Models\CursoAcademico;
use App\Models\Alumno;
use App\Models\Empresa;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AlumnosImport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CsvImportTest extends TestCase
{
    // usage: php artisan test --filter CsvImportTest

    public function test_can_import_students_from_csv()
    {
        // Setup
        $user = User::factory()->create(); // Assuming factory exists
        $curso = Curso::factory()->create();
        $cursoAcademico = CursoAcademico::factory()->create(['actual' => true]);
        $empresa = Empresa::factory()->create(['nombre' => 'Test Corp']);

        // Mock CSV Content
        // Header + Row
        $header = "Alumno/a;DNI;Nota1;Nota2;Nota3;Nota4;Nota5;Nota6;Nota7;Nota8;Media;Empresa;DNI Enc";
        $row1 = "Juan Test;12345678Z;5;6;7;8;9;10;5;6;7.0;Test Corp;12**78**Z";
        
        $content = "$header\n$row1";
        
        $file = UploadedFile::fake()->createWithContent('alumnos.csv', $content);

        // Act
        $response = $this->actingAs($user)
            ->post(route('alumnos.importar', [
                'curso' => $curso->id, 
                'cursoAcademico' => $cursoAcademico->id
            ]), [
                'fichero_alumnos' => $file
            ]);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('alumnos', [
            'dni' => '12345678Z',
            'nombre_completo' => 'Juan Test',
            'curso_id' => $curso->id,
            'curso_academico_id' => $cursoAcademico->id,
            'empresa_id' => $empresa->id,
            'nota_1' => 5,
        ]);
    }
}
