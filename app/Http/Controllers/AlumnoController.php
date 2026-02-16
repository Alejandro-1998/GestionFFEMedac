<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use \Illuminate\Validation\Rules\Password;
use \Illuminate\Support\Facades\Hash;

class AlumnoController extends Controller
{
    /**
     * Listado de Alumnos.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Alumno::with(['empresa', 'curso']);

        // Filter by professor's courses
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user && $user->rol === 'profesor') {
            $cursoIds = $user->cursos()->pluck('cursos_academicos.id');
            $query->whereIn('curso_academico_id', $cursoIds);
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre_completo', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        if ($request->has('curso_academico_id') && $request->curso_academico_id != '') {
            $query->where('curso_academico_id', $request->curso_academico_id);
        }

        if ($user && $user->rol === 'profesor') {
            $cursos = $user->cursos;
        } else {
            $cursos = \App\Models\CursoAcademico::all();
        }
        $alumnos = $query->paginate(10);
        if ($request->ajax()) {
            return view('alumnos.partials.table-rows', compact('alumnos', 'cursos'));
        }

        return view('alumnos.index', compact('alumnos', 'cursos'));
    }

    /**
     * Guarda un Alumno.
     */
    public function store(Request $request)
    {
        // Sanitize grades (replace comma with dot)
        $input = $request->all();
        foreach (['nota_1', 'nota_2', 'nota_3', 'nota_4', 'nota_5', 'nota_6', 'nota_7', 'nota_8', 'nota_media'] as $field) {
            if (isset($input[$field]) && is_string($input[$field])) {
                $input[$field] = str_replace(',', '.', $input[$field]);
            }
        }
        $request->merge($input);

        $request->validate([
            'nombre_completo' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:15', 'unique:alumnos,dni'],
            'nota_media' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'curso_academico_id' => ['nullable', 'exists:cursos_academicos,id'],
            'nota_1' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'nota_2' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'nota_3' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'nota_4' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'nota_5' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'nota_6' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'nota_7' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'nota_8' => ['nullable', 'numeric', 'min:0', 'max:10'],
        ]);

        \App\Models\Alumno::create($request->all());

        return redirect()->route('alumnos.index')->with('success', 'Alumno creado correctamente.');
    }

    /**
     * Muestra un Alumno.
     */
    public function show(string $id)
    {
        $alumno = \App\Models\Alumno::with(['empresa', 'curso'])->findOrFail($id);
        return view('alumnos.show', compact('alumno'));
    }

    /**
     * Actualiza un Alumno.
     */
    public function update(Request $request, string $id)
    {
        // Sanitize grades (replace comma with dot)
        $input = $request->all();
        foreach (['nota_1', 'nota_2', 'nota_3', 'nota_4', 'nota_5', 'nota_6', 'nota_7', 'nota_8', 'nota_media'] as $field) {
            if (isset($input[$field]) && is_string($input[$field])) {
                $input[$field] = str_replace(',', '.', $input[$field]);
            }
        }
        $request->merge($input);

        $request->validate([
            'nombre_completo' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:15', 'unique:alumnos,dni,' . $id],
            'nota_media' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'curso_academico_id' => ['nullable', 'exists:cursos_academicos,id'],
            'nota_1' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'nota_2' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'nota_3' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'nota_4' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'nota_5' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'nota_6' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'nota_7' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'nota_8' => ['nullable', 'numeric', 'min:0', 'max:10'],
        ]);

        $alumno = \App\Models\Alumno::findOrFail($id);
        $alumno->update($request->all());

        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado correctamente.');
    }

    /**
     * Elimina un Alumno.
     */
    public function destroy(string $id)
    {
        $alumno = \App\Models\Alumno::findOrFail($id);
        $alumno->delete();
        return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado correctamente.');
    }
}
