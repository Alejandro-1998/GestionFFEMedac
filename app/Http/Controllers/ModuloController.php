<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Curso;
use App\Models\CursoAcademico;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

class ModuloController extends Controller
{
    /**
     * Listado de todos los módulos.
     */
    public function index()
    {
        $cursoActual = CursoAcademico::where('actual', true)->first();

        if ($cursoActual) {
            $modulosActivos = $cursoActual->modulos;
            $otrosModulos = Modulo::whereDoesntHave('cursosAcademicos', function ($query) use ($cursoActual) {
                $query->where('id', $cursoActual->id);
            })->get();
        } else {
            $modulosActivos = collect();
            $otrosModulos = Modulo::all();
        }

        return view('modulos.index', compact('modulosActivos', 'otrosModulos'));
    }

    /**
     * Guardar un nuevo módulo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('modulos')
            ],
            'duracion' => 'required|in:1_anyo,2_anyos',
        ], [
            'nombre.unique' => 'No ha sido posible crear el módulo porque el nombre coincide con otro existente.',
        ]);

        $modulo = Modulo::create([
            'nombre' => $request->nombre,
        ]);

        // Create associated Cursos based on duration
        $cursosParaCrear = match ($request->duracion) {
            '2_anyos' => ['1º', '2º'],
            '1_anyo' => ['1º'],
            default => ['1º', '2º'], // Fallback
        };

        foreach ($cursosParaCrear as $nombreCurso) {
            Curso::create([
                'nombre' => $nombreCurso,
                'modulo_id' => $modulo->id,
            ]);
        }

        return redirect()->back()->with('success', 'Módulo creado correctamente.');
    }

    /**
     * Eliminar un módulo.
     */
    public function destroy($id)
    {
        $modulo = Modulo::findOrFail($id);
        $modulo->delete();

        return redirect()->back()->with('success', 'Módulo eliminado correctamente.');
    }
    /**
     * Mostrar alumnos de un módulo en el curso académico actual.
     */
    public function showAlumnos($id)
    {
        $modulo = Modulo::with('cursos')->findOrFail($id);
        $cursoActual = CursoAcademico::where('actual', true)->first();

        if (!$cursoActual) {
            return redirect()->back()->with('error', 'No hay un curso académico activo.');
        }

        // Get module courses (1º, 2º) IDs
        $cursoIds = $modulo->cursos->pluck('id');

        // Fetch students enrolled in these courses AND the active academic year
        $alumnos = \App\Models\Alumno::whereIn('curso_id', $cursoIds)
            ->where('curso_academico_id', $cursoActual->id)
            ->with(['empresa', 'curso'])
            ->orderBy('nombre_completo')
            ->get();

        return view('modulos.show_alumnos', compact('modulo', 'alumnos', 'cursoActual'));
    }
}
