<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Models\Empresa;
use App\Models\User;
use App\Models\CursoAcademico;
use App\Models\Alumno;
use App\Models\Convenio;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\CursoAcademicoController;

Route::get('/', function () {
    $stats = [
        'empresas' => Empresa::count(),
        'alumnos' => Alumno::count(),
        'cursos' => CursoAcademico::count(),
        'convenios' => Convenio::count(),
    ];
    return view('welcome', compact('stats'));
})->name('welcome');

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

use App\Http\Controllers\ConvenioController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('empresas', EmpresaController::class)->middleware('can:access-management');
    Route::resource('empleados', EmpleadoController::class)->middleware('can:access-management');
    Route::resource('sedes', SedeController::class)->middleware('can:access-management');
    Route::get('/modulos/{id}/alumnos', [ModuloController::class, 'showAlumnos'])->name('modulos.showAlumnos')->middleware('can:access-management');
    Route::get('/cursos/{curso}/alumnos-actuales', [AlumnoController::class, 'listadoCursoActual'])->name('alumnos.curso-actual')->middleware('can:access-management');
    Route::get('/cursos/{curso}/academico/{cursoAcademico}/alumnos', [AlumnoController::class, 'listadoPorCursoYAcademico'])->name('alumnos.curso-academico')->middleware('can:access-management');
    Route::get('/cursos/{curso}/academico/{cursoAcademico}/pdf', [AlumnoController::class, 'exportarPdfListado'])->name('alumnos.exportar-pdf')->middleware('can:access-management');
    Route::resource('alumnos', AlumnoController::class)->middleware('can:access-management');
    Route::resource('cursos', CursoAcademicoController::class);
    Route::post('/cursos/{id}/sync-modulos', [CursoAcademicoController::class, 'syncModulos'])->name('cursos.syncModulos')->middleware('can:access-management');
    Route::post('/cursos/{id}/actual', [CursoAcademicoController::class, 'marcarComoActual'])->name('cursos.actual')->middleware('can:access-management');
    Route::post('/convenios/bulk-update-hours', [ConvenioController::class, 'bulkUpdateHours'])->name('convenios.bulkUpdateHours')->middleware('can:access-management');
    Route::resource('convenios', ConvenioController::class)->middleware('can:access-management');
    Route::post('/cursos/{id}/importar-alumnos', [CursoAcademicoController::class, 'importarAlumnos'])->name('cursos.importarAlumnos')->middleware('can:access-management');
    Route::get('/cursos/{id}/exportar-pdf', [CursoAcademicoController::class, 'exportarPdf'])->name('cursos.exportarPdf')->middleware('can:access-management');
    Route::resource('modulos', \App\Http\Controllers\ModuloController::class)->middleware('can:access-management');
    Route::resource('profesores', \App\Http\Controllers\ProfesorController::class)->middleware('can:admin');
    Route::get('/configuracion', [\App\Http\Controllers\ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('/configuracion', [\App\Http\Controllers\ConfiguracionController::class, 'update'])->name('configuracion.update');
});

require __DIR__ . '/auth.php';
