<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use \Illuminate\Validation\Rules\Password;
use \Illuminate\Support\Facades\Hash;
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
        // Eager load curso (and its module -> years) and the direct cursoAcademico
        $query = Alumno::with(['empresa', 'curso.modulo', 'cursoAcademico']);

        // Filter by professor's courses not directly implemented yet in new schema for brevity
        // Assuming Admin for now or need to traverse User -> Curso -> Alumno
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre_completo', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        $cursosDisponibles = collect(); // Default empty collection

        // Filter by Academic Year (via nested relationship)
        if ($request->filled('curso_academico_id')) {
             $query->where('curso_academico_id', $request->curso_academico_id);

             // Fetch available modules/courses for this academic year
             $cursosDisponibles = Curso::whereHas('modulo.cursosAcademicos', function($q) use ($request) {
                 $q->where('curso_academico_modulo.curso_academico_id', $request->curso_academico_id);
             })->with('modulo')->get();
        } else {
             // If no academic year selected, show all courses
             $cursosDisponibles = Curso::with('modulo')->get();
        }

        // Filter by specific Module/Course
        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        $cursos = CursoAcademico::with(['modulos.cursos'])->get(); // For the creation form dropdowns
        
        $alumnos = $query->paginate(10)->withQueryString();
        
        if ($request->ajax()) {
            return view('alumnos.partials.table-rows', compact('alumnos', 'cursos', 'cursosDisponibles'));
        }

        return view('alumnos.index', compact('alumnos', 'cursos', 'cursosDisponibles'));
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

        // Generate Email if not provided (or overwrite to ensure uniqueness/format)
        // User request: "los correos de los alumnos deberian ser unicos"
        // We will generate based on name if not provided, or sanitize provided one.
        // Actually, let's auto-generate to be safe if it's a common pattern, 
        // OR just ensure uniqueness by appending count. 
        // Let's assume we take the provided email or generate from name, then ensure uniqueness.
        
        $baseEmail = $input['email'] ?? null;
        if (!$baseEmail && isset($input['nombre_completo'])) {
            // Generate from name: juan.perez
             $parts = explode(' ', strtolower($this->removeAccents($input['nombre_completo'])));
             $baseEmail = $parts[0] . (isset($parts[1]) ? '.' . $parts[1] : '');
        }

        // Clean base email
        $baseEmail = str_replace('@alu.medac.es', '', $baseEmail);
        $baseEmail = preg_replace('/[^a-z0-9\.]/', '', strtolower($baseEmail));

        // Ensure uniqueness
        $email = $baseEmail . '@alu.medac.es';
        $counter = 1;
        while (Alumno::where('email', $email)->exists()) {
            $email = $baseEmail . $counter . '@alu.medac.es';
            $counter++;
        }
        $input['email'] = $email;

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
        // Sanitize grades (replace comma with dot)
        $input = $request->all();
        foreach (['nota_1', 'nota_2', 'nota_3', 'nota_4', 'nota_5', 'nota_6', 'nota_7', 'nota_8', 'nota_media'] as $field) {
            if (isset($input[$field]) && is_string($input[$field])) {
                $input[$field] = str_replace(',', '.', $input[$field]);
            }
        }

        // Unique Email Logic for Update
        // If email is empty, generate it. If provided, sanitise/uniquify it ONLY if it changed or if user wants us to correct it.
        // Actually, for update, we should trust the user input mainly, but apply unique check logic if they changed it.
        
        $baseEmail = $input['email'] ?? null;
        $currentAlumno = Alumno::find($id);
        
        // Only regenerate if email field is being updated and it is different or empty
        if ((!$baseEmail && $currentAlumno) || ($baseEmail && $currentAlumno && $baseEmail !== $currentAlumno->email)) {
             if (!$baseEmail && isset($input['nombre_completo'])) {
                 $parts = explode(' ', strtolower($this->removeAccents($input['nombre_completo'])));
                 $baseEmail = $parts[0] . (isset($parts[1]) ? '.' . $parts[1] : '');
             }
             
             // Clean base email
             $baseEmail = str_replace('@alu.medac.es', '', $baseEmail);
             $baseEmail = preg_replace('/[^a-z0-9\.]/', '', strtolower($baseEmail));

             // Ensure uniqueness (accounting for self)
             $email = $baseEmail . '@alu.medac.es';
             $counter = 1;
             while (Alumno::where('email', $email)->where('id', '!=', $id)->exists()) {
                 $email = $baseEmail . $counter . '@alu.medac.es';
                 $counter++;
             }
             $input['email'] = $email;
        } elseif ($baseEmail && !str_ends_with($baseEmail, '@alu.medac.es')) {
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
        $cursoAcademico = CursoAcademico::find($currentYearId); // Load the model for the view

        $alumnos = Alumno::where('curso_id', $curso->id)
            ->where('curso_academico_id', $currentYearId)
            ->with(['empresa', 'curso']) // Eager load
            ->get()
            ->sortBy('nombre_completo'); // Sorting by name since it's a single string

        return view('alumnos.listado_curso', compact('alumnos', 'curso', 'cursoAcademico'));
    }

    public function listadoPorCursoYAcademico(Curso $curso, CursoAcademico $cursoAcademico)
    {
        $alumnos = Alumno::where('curso_id', $curso->id)
            ->where('curso_academico_id', $cursoAcademico->id)
            ->with(['empresa', 'curso'])
            ->get()
            ->sortBy('nombre_completo');

        return view('alumnos.listado_curso', compact('alumnos', 'curso', 'cursoAcademico'));
    }

    public function exportarPdfListado(Curso $curso, CursoAcademico $cursoAcademico)
    {
        $alumnos = Alumno::where('curso_id', $curso->id)
            ->where('curso_academico_id', $cursoAcademico->id)
            ->with(['empresa', 'curso'])
            ->get()
            ->sortBy('nombre_completo');

        // Use a landscape view or standard portrait depending on data. 
        // Portrait usually fits 3 columns fine.
        $pdf = Pdf::loadView('cursos.pdf_alumnos', compact('alumnos', 'curso', 'cursoAcademico'));
        
        // Return stream or download
        return $pdf->download('Listado_Alumnos_' . $curso->nombre . '_' . $cursoAcademico->anyo . '.pdf');
    }

    private function removeAccents($string) {
        $accents = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N'
        ];
        return strtr($string, $accents);
    }
}
