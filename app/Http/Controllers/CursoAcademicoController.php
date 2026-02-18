<?php

namespace App\Http\Controllers;

use App\Models\CursoAcademico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CursoAcademicoController extends Controller
{
    /**
     * Marcar un curso como actual.
     */
    public function marcarComoActual($id)
    {
        // Set all to false
        CursoAcademico::query()->update(['actual' => false]);
        
        // Set the requested one to true
        $curso = CursoAcademico::findOrFail($id);
        $curso->update(['actual' => true]);

        return redirect()->back()->with('success', 'Curso marcado como actual correctamente.');
    }

    /**
     * Listado de Cursos Académicos (Años).
     */
    public function index(Request $request)
    {
        $query = CursoAcademico::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('anyo', 'like', "%{$search}%");
        }

        $cursos = $query->orderBy('anyo', 'desc')->paginate(10);
        
        if ($request->ajax()) {
            return view('cursos.partials.cards', compact('cursos'));
        }

        return view('cursos.index', compact('cursos'));
    }

    /**
     * Guardar un Curso Académico (Año).
     */
    public function store(Request $request)
    {
        $request->validate([
            'anyo' => 'required|string|max:255|unique:cursos_academicos,anyo',
        ]);

        CursoAcademico::create([
            'anyo' => $request->anyo,
        ]);

        return redirect()->route('cursos.index')->with('success', 'Año académico creado correctamente.');
    }

    /**
     * Muestra un Curso Académico (Año) y sus Módulos.
     */
    /**
     * Muestra un Curso Académico (Año) y sus Módulos.
     */
    public function show($id)
    {
        $curso = CursoAcademico::with('modulos.cursos')->findOrFail($id);
        $todosLosModulos = \App\Models\Modulo::all();
        
        return view('cursos.show', compact('curso', 'todosLosModulos'));
    }

    /**
     * Sincronizar módulos con el curso académico.
     */
    public function syncModulos(Request $request, $id)
    {
        $curso = CursoAcademico::findOrFail($id);
        $curso->modulos()->sync($request->input('modulos', []));

        return redirect()->back()->with('success', 'Módulos actualizados correctamente.');
    }

    /**
     * Actualiza un Curso Académico.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'anyo' => 'required|string|max:255|unique:cursos_academicos,anyo,' . $id,
        ]);

        $curso = CursoAcademico::findOrFail($id);
        $curso->update([
            'anyo' => $request->anyo,
        ]);

        return redirect()->back()->with('success', 'Año académico actualizado correctamente.');
    }

    /**
     * Elimina un Curso Académico.
     */
    public function destroy($id)
    {
        $curso = CursoAcademico::findOrFail($id);
        // Cascade delete should handle related modules/courses via DB constraints? 
        // Or we can delete related manually if needed but DB constrained ->onDelete('cascade') is set for modulos.
        $curso->delete();

        return redirect()->route('cursos.index')->with('success', 'Año académico eliminado correctamente.');
    }
    /**
     * Importar Alumnos desde CSV/Excel
     */
    public function importarAlumnos(Request $request, $id)
    {
        $request->validate([
            'fichero_alumnos' => 'required|file|mimes:csv,txt,xlsx,xls',
        ]);

        $cursoAcademico = CursoAcademico::findOrFail($id);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\AlumnosImport($id, null), $request->file('fichero_alumnos'));
            return redirect()->back()->with('success', 'Alumnos importados correctamente.');
        } catch (\Exception $e) {
            Log::error('Error importando alumnos: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al importar alumnos: ' . $e->getMessage());
        }
    }
}
