<?php
// Script to be piped into tinker

// ... (same content as before but ensuring no syntax errors for tinker)
use App\Models\Alumno;
use App\Models\CursoAcademico;
use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Support\Facades\Schema;

echo "--- START VERIFICATION ---\n";

// Check Schema
$colExists = Schema::hasColumn('alumnos', 'curso_academico_id');
echo "Column 'curso_academico_id' exists: " . ($colExists ? "YES" : "NO") . "\n";

echo "--- END VERIFICATION ---\n";
exit;

