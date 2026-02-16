<?php

namespace App\Http\Controllers;

use App\Models\CursoAcademico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CursoAcademicoController extends Controller
{
    /**
     * Listado de Cursos Académicos.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user->rol === 'profesor') {
            $query = $user->cursos()->getQuery();
        } else {
            $query = CursoAcademico::query();
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('anyo', 'like', "%{$search}%");
        }

        $cursos = $query->orderBy('cursos_academicos.id', 'asc')->paginate(10);
        
        if ($request->ajax()) {
            return view('cursos.partials.cards', compact('cursos'));
        }

        return view('cursos.index', compact('cursos'));
    }

    /**
     * Guardar un Curso Académico.
     */
    public function store(Request $request)
    {
        $request->validate([
            'anyo' => 'required|string|max:255',

        ]);

        CursoAcademico::create([
            'anyo' => $request->anyo,

        ]);

        return redirect()->route('cursos.index')->with('success', 'Curso académico creado correctamente.');
    }

    /**
     * Muestra un Curos Académico.
     */
    public function show($id)
    {
        $curso = CursoAcademico::with(['convenios.alumno', 'convenios.empresa', 'alumnos.empresa'])->findOrFail($id);
        
        // Sort students by surname (heuristic: string after first space)
        $sortedAlumnos = $curso->alumnos->sortBy(function($alumno) {
            $parts = explode(' ', $alumno->nombre_completo, 2);
            return isset($parts[1]) ? $parts[1] . ' ' . $parts[0] : $parts[0];
        })->values();
        
        $curso->setRelation('alumnos', $sortedAlumnos);
        
        return view('cursos.show', compact('curso'));
    }

    /**
     * Actualiza un Curso Académico.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'anyo' => 'required|string|max:255',

        ]);

        $curso = CursoAcademico::findOrFail($id);
        $curso->update([
            'anyo' => $request->anyo,

        ]);

        return redirect()->route('cursos.index')->with('success', 'Curso académico actualizado correctamente.');
    }

    /**
     * Elimina un Curso Académico.
     */
    public function destroy($id)
    {
        $curso = CursoAcademico::findOrFail($id);
        $curso->delete();

        return redirect()->route('cursos.index')->with('success', 'Curso académico eliminado correctamente.');
    }

    /**
     * Importar alumnos desde Excel.
     */
    public function importarAlumnos(Request $request, $id)
    {
        $request->validate([
            'archivo_excel' => 'required|file|mimes:xlsx,xls,csv,txt',
        ]);

        $curso = CursoAcademico::findOrFail($id);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\AlumnosImport($curso->id), $request->file('archivo_excel'));
            return redirect()->route('cursos.show', $curso->id)->with('success', 'Alumnos importados correctamente.');
        } catch (\Exception $e) {
            Log::error('Controller Import Error: ' . $e->getMessage());
            return redirect()->route('cursos.show', $curso->id)->with('error', 'Error al importar alumnos: ' . $e->getMessage());
        }
    }
    /**
     * Exportar alumnos a PDF.
     */
    public function exportarPdf($id)
    {
        $curso = CursoAcademico::with(['alumnos.empresa'])->findOrFail($id);
        
        // Sort students by surname (heuristic: string after first space)
        $sortedAlumnos = $curso->alumnos->sortBy(function($alumno) {
            $parts = explode(' ', $alumno->nombre_completo, 2);
            return isset($parts[1]) ? $parts[1] . ' ' . $parts[0] : $parts[0];
        })->values();
        
        $curso->setRelation('alumnos', $sortedAlumnos);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cursos.pdf_alumnos', compact('curso'));
        
        return $pdf->download('listado_alumnos_' . \Illuminate\Support\Str::slug($curso->anyo) . '.pdf');
    }
}
