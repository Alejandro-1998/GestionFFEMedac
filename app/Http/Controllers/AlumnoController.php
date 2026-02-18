<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Curso;
use App\Models\CursoAcademico;
use App\Models\Alumno;
use Barryvdh\DomPDF\Facade\Pdf;

class AlumnoController extends Controller
{
    /**
     * Listado de Alumnos.
     */
    public function index(Request $request)
    {
        $query = Alumno::with(['empresa', 'curso.modulo', 'cursoAcademico']);

        // Buscador
        if ($request->has('search')) {

            $search = $request->input('search');

            $query->where(function ($q) use ($search) {

                $q->where('nombre_completo', 'like', "%{$search}%")->orWhere('dni', 'like', "%{$search}%");
            });
        }

        $cursosDisponibles = collect();

        // Filtro Curso Académico
        if ($request->filled('curso_academico_id')) {

            $query->where('curso_academico_id', $request->curso_academico_id);

            $cursosDisponibles = Curso::whereHas('modulo.cursosAcademicos', function ($q) use ($request) {

                $q->where('curso_academico_modulo.curso_academico_id', $request->curso_academico_id);
            })->with('modulo')->get();
        }
        else {

            $cursosDisponibles = Curso::with('modulo')->get();
        }

        // Filter Módulo
        if ($request->filled('curso_id')) $query->where('curso_id', $request->curso_id);

        $cursos = CursoAcademico::with(['modulos.cursos'])->get();

        $alumnos = $query->paginate(10)->withQueryString();

        if ($request->ajax()) return view('alumnos.partials.table-rows', compact('alumnos', 'cursos', 'cursosDisponibles'));

        return view('alumnos.index', compact('alumnos', 'cursos', 'cursosDisponibles'));
    }

    /**
     * Guarda un Alumno.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        foreach (['nota_1', 'nota_2', 'nota_3', 'nota_4', 'nota_5', 'nota_6', 'nota_7', 'nota_8', 'nota_media'] as $field) {

            if (isset($input[$field]) && is_string($input[$field])) $input[$field] = str_replace(',', '.', $input[$field]);
        }

        $baseEmail = $input['email'] ?? null;

        if (!$baseEmail && isset($input['nombre_completo'])) {

            $parts = explode(' ', strtolower($this->removeAccents($input['nombre_completo'])));
            $baseEmail = $parts[0] . (isset($parts[1]) ? '.' . $parts[1] : '');
        }

        // Formatear Email
        $baseEmail = str_replace('@alu.medac.es', '', $baseEmail);
        $baseEmail = preg_replace('/[^a-z0-9\.]/', '', strtolower($baseEmail));

        $email = $baseEmail . '@alu.medac.es';
        $counter = 1;

        while (Alumno::where('email', $email)->exists()) {

            $email = $baseEmail . $counter . '@alu.medac.es';
            $counter++;
        }

        $email =$input['email'];

        $request->merge($input);

        $request->validate([
            'nombre_completo' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:15', 'unique:alumnos,dni'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:alumnos,email'],
            'nota_media' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'curso_id' => ['nullable', 'exists:cursos,id'],
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

        Alumno::create($request->all());

        return redirect()->route('alumnos.index')->with('success', 'Alumno creado correctamente.');
    }

    /**
     * Muestra un Alumno.
     */
    public function show(string $id)
    {
        $alumno = Alumno::with(['empresa', 'curso.modulo.cursosAcademicos'])->findOrFail($id);

        return view('alumnos.show', compact('alumno'));
    }

    /**
     * Actualiza un Alumno.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();

        foreach (['nota_1', 'nota_2', 'nota_3', 'nota_4', 'nota_5', 'nota_6', 'nota_7', 'nota_8', 'nota_media'] as $field) {

            if (isset($input[$field]) && is_string($input[$field])) $input[$field] = str_replace(',', '.', $input[$field]);
        }

        $baseEmail = $input['email'] ?? null;
        $currentAlumno = Alumno::find($id);

        if ((!$baseEmail && $currentAlumno) || ($baseEmail && $currentAlumno && $baseEmail !== $currentAlumno->email)) {

            if (!$baseEmail && isset($input['nombre_completo'])) {

                $parts = explode(' ', strtolower($this->removeAccents($input['nombre_completo'])));
                $baseEmail = $parts[0] . (isset($parts[1]) ? '.' . $parts[1] : '');
            }

            // Formatear Email
            $baseEmail = str_replace('@alu.medac.es', '', $baseEmail);
            $baseEmail = preg_replace('/[^a-z0-9\.]/', '', strtolower($baseEmail));

            $email = $baseEmail . '@alu.medac.es';
            $counter = 1;

            while (Alumno::where('email', $email)->where('id', '!=', $id)->exists()) {

                $email = $baseEmail . $counter . '@alu.medac.es';
                $counter++;
            }

            $input['email'] = $email;
        }
        elseif ($baseEmail && !str_ends_with($baseEmail, '@alu.medac.es')) {

            $input['email'] = $baseEmail . '@alu.medac.es';
        }

        $request->merge($input);

        $request->validate([
            'nombre_completo' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:15', 'unique:alumnos,dni,' . $id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:alumnos,email,' . $id],
            'nota_media' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'curso_id' => ['nullable', 'exists:cursos,id'],
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

        $alumno = Alumno::findOrFail($id);
        $alumno->update($request->all());

        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado correctamente.');
    }

    /**
     * Elimina un Alumno.
     */
    public function destroy(string $id)
    {
        $alumno = Alumno::findOrFail($id);
        $alumno->delete();

        return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado correctamente.');
    }

    public function listadoCursoActual(Curso $curso)
    {
        $currentYearId = CursoAcademico::where('actual', true)->value('id');
        $cursoAcademico = CursoAcademico::find($currentYearId);

        $alumnos = Alumno::where('curso_id', $curso->id)
            ->where('curso_academico_id', $currentYearId)
            ->with(['empresa', 'curso'])
            ->get()
            ->sortBy(function ($alumno) {
                $parts = explode(' ', $alumno->nombre_completo, 2);
                return isset($parts[1]) ? $parts[1] : $parts[0];
            }, SORT_NATURAL | SORT_FLAG_CASE);

        return view('alumnos.listado_curso', compact('alumnos', 'curso', 'cursoAcademico'));
    }

    public function listadoPorCursoYAcademico(Curso $curso, CursoAcademico $cursoAcademico)
    {
        $alumnos = Alumno::where('curso_id', $curso->id)
            ->where('curso_academico_id', $cursoAcademico->id)
            ->with(['empresa', 'curso'])
            ->get()
            ->sortBy(function ($alumno) {
                $parts = explode(' ', $alumno->nombre_completo, 2);
                return isset($parts[1]) ? $parts[1] : $parts[0];
            }, SORT_NATURAL | SORT_FLAG_CASE);

        return view('alumnos.listado_curso', compact('alumnos', 'curso', 'cursoAcademico'));
    }

    public function exportarPdfListado(Curso $curso, CursoAcademico $cursoAcademico)
    {
        $alumnos = Alumno::where('curso_id', $curso->id)
            ->where('curso_academico_id', $cursoAcademico->id)
            ->with(['empresa', 'curso'])
            ->get()
            ->sortBy(function ($alumno) {
                $parts = explode(' ', $alumno->nombre_completo, 2);
                return isset($parts[1]) ? $parts[1] : $parts[0];
            }, SORT_NATURAL | SORT_FLAG_CASE);

        // Use a landscape view or standard portrait depending on data. 
        // Portrait usually fits 3 columns fine.
        $pdf = Pdf::loadView('cursos.pdf_alumnos', compact('alumnos', 'curso', 'cursoAcademico'));

        // Return stream or download
        return $pdf->download('Listado_Alumnos_' . $curso->nombre . '_' . $cursoAcademico->anyo . '.pdf');
    }

    public function importarAlumnos(Request $request, Curso $curso, CursoAcademico $cursoAcademico)
    {
        $request->validate([
            'fichero_alumnos' => 'required|file|mimes:csv,txt,xlsx,xls',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\AlumnosImport($cursoAcademico->id, $curso->id), $request->file('fichero_alumnos'));
            return redirect()->back()->with('success', 'Alumnos importados correctamente al curso ' . $curso->nombre);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error importando alumnos: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al importar alumnos: ' . $e->getMessage());
        }
    }

    private function removeAccents($string)
    {
        $accents = [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'Á' => 'A',
            'É' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ú' => 'U',
            'ñ' => 'n',
            'Ñ' => 'N'
        ];
        return strtr($string, $accents);
    }
}
