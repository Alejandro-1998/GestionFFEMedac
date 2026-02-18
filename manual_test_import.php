<?php

use App\Models\CursoAcademico;
use App\Models\Curso;
use App\Models\Empresa;
use App\Models\Alumno;
use App\Imports\AlumnosImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Helper to create dummy data if needed, or fetch existing
$cursoAcademico = CursoAcademico::first() ?? CursoAcademico::create(['anyo' => '2024-2025', 'actual' => true]);
$curso = Curso::first() ?? Curso::create(['nombre' => 'DAW', 'modulo_id' => 1]); // Adjust modulo_id if necessary
$empresa = Empresa::firstOrCreate(['nombre' => 'Test Corp'], ['cif' => 'B12345678', 'direccion' => 'Test Dir', 'telefono' => '123456789']);

echo "Testing Import for Curso: {$curso->nombre} (ID: {$curso->id}) and Academic Year: {$cursoAcademico->anyo} (ID: {$cursoAcademico->id})\n";

// Create a temporary CSV file
$csvContent = "Alumno/a;DNI;Nota1;Nota2;Nota3;Nota4;Nota5;Nota6;Nota7;Nota8;Media;Empresa;DNI Enc\n" .
              "Juan Manual Test;99999999Z;5;6;7;8;9;10;5;6;7.0;Test Corp;99**99**Z";

$filePath = sys_get_temp_dir() . '/test_alumnos.csv';
file_put_contents($filePath, $csvContent);

$file = new UploadedFile($filePath, 'test_alumnos.csv', 'text/csv', null, true);

try {
    echo "Running Import...\n";
    // We can't easily mock Excel facade here without full app. 
    // Instead, let's instantiate the Import class and call its collection method directly if possible, 
    // OR just rely on the Import class logic.
    // The Excel::import() is a wrapper. 
    // Let's rely on the Import class directly if we can mock the rows collection.

    // Better: Use the Import class logic directly by creating a collection of rows
    $import = new AlumnosImport($cursoAcademico->id, $curso->id);
    
    // Simulate rows (skipping header as startRow does)
    $rows = collect([
        // Row 0 is header in file but collection usually receives data based on config
        // Maatwebsite usually passes the data rows.
        ['Juan Manual Test', '99999999Z', '5', '6', '7', '8', '9', '10', '5', '6', '7.0', 'Test Corp', '99**99**Z']
    ]);

    $import->collection($rows);

    echo "Import execution finished.\n";
    
    $alumno = Alumno::where('dni', '99999999Z')->first();
    if ($alumno) {
        echo "SUCCESS: Alumno created.\n";
        echo "Name: " . $alumno->nombre_completo . "\n";
        echo "Curso ID: " . $alumno->curso_id . "\n";
        echo "Empresa ID: " . $alumno->empresa_id . "\n";
    } else {
        echo "FAILURE: Alumno not found.\n";
    }

} catch (\Exception $e) {
    echo "ERROR MSG: " . $e->getMessage() . "\n";
    exit(1);
}

// Cleanup
Alumno::where('dni', '99999999Z')->delete();
if(file_exists($filePath)) unlink($filePath);
