<?php

namespace App\Http\Controllers;

use App\Models\Convenio;
use App\Models\User;
use App\Models\Empresa;
use App\Models\CursoAcademico;
use Illuminate\Http\Request;

class ConvenioController extends Controller
{
    /**
     * Listado de Convenios.
     */
    public function index(Request $request)
    {
        $query = Convenio::with(['alumno', 'empresa', 'curso']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('alumno', function($subQ) use ($search) {
                    $subQ->where('nombre_completo', 'like', "%{$search}%");
                })->orWhereHas('empresa', function($subQ) use ($search) {
                    $subQ->where('nombre', 'like', "%{$search}%");
                });
            });
        }

        if ($request->has('curso_academico_id') && $request->curso_academico_id != '') {
            $query->where('curso_academico_id', $request->curso_academico_id);
        }

        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        $convenios = $query->paginate(10);
        $cursos = CursoAcademico::all();
        
        return view('convenios.index', compact('convenios', 'cursos'));
    }

    /**
     * Muestra un Convenio.
     */
    public function show($id)
    {
        $convenio = Convenio::with(['alumno', 'empresa', 'curso', 'sede', 'tutorLaboral', 'profesor'])->findOrFail($id);
        return view('convenios.show', compact('convenio'));
    }
    /**
     * Formulario de creación.
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user && $user->rol === 'profesor') {
            $cursoIds = $user->cursos()->pluck('cursos_academicos.id');

            $alumnos = \App\Models\Alumno::whereHas('curso.modulo', function($q) use ($cursoIds) {
                            $q->whereIn('curso_academico_id', $cursoIds);
                        })
                        ->whereDoesntHave('convenios')
                        ->get();
            $cursos = $user->cursos;
        } else {
            $alumnos = \App\Models\Alumno::whereDoesntHave('convenios')->get();
            $cursos = CursoAcademico::all();
        } 
        
        $profesores = User::where('rol', 'profesor')->get(); 
        $empresas = Empresa::with(['sedes', 'empleados'])->get();

        return view('convenios.create', compact('alumnos', 'profesores', 'empresas', 'cursos'));
    }

    /**
     * Crear Convenio
     */
    public function store(Request $request)
    {
        $request->validate([
            'alumno_id' => 'required|exists:alumnos,id|unique:convenios,alumno_id',
            'curso_academico_id' => 'required|exists:cursos_academicos,id',
            'empresa_id' => 'required|exists:empresas,id',
        ], [
            'alumno_id.exists' => 'El alumno seleccionado no es válido.',
            'alumno_id.required' => 'Debes seleccionar un alumno.',
            'curso_academico_id.exists' => 'El curso académico seleccionado no es válido.',
            'curso_academico_id.required' => 'Debes seleccionar un curso académico.',
            'empresa_id.exists' => 'La empresa seleccionada no es válida.',
            'empresa_id.required' => 'Debes seleccionar una empresa.',
        ]);

        $convenio = Convenio::create([
            'alumno_id' => $request->alumno_id,
            'curso_academico_id' => $request->curso_academico_id,
            'empresa_id' => $request->empresa_id,   
        ]);

        return redirect()->route('convenios.index')->with('success', 'Convenio creado correctamente. Ahora puedes completar los detalles.');
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $convenio = Convenio::with(['alumno', 'empresa.sedes', 'empresa.empleados'])->findOrFail($id);
        $profesores = User::where('rol', 'profesor')->get(); 

        return view('convenios.edit', compact('convenio', 'profesores'));
    }

    /**
     * Actualización de Convenio.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'sede_id' => 'required|exists:sedes,id',
            'empleado_id' => 'required|exists:empleados,id',
            'profesor_id' => 'required|exists:users,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'total_horas' => 'required|integer|min:1',
            'estado' => 'required|in:asignada,en_proceso,finalizada,cancelada',
        ]);

        $convenio = Convenio::findOrFail($id);
        $convenio->update($request->all());

        return redirect()->route('convenios.index')->with('success', 'Convenio actualizado correctamente.');
    }
    /**
     * Eliminación de Convenio.
     */
    public function destroy($id)
    {
        $convenio = Convenio::findOrFail($id);
        $convenio->delete();

        return redirect()->route('convenios.index')->with('success', 'Convenio eliminado correctamente.');
    }

    public function bulkUpdateHours()
    {
        $horas1 = \App\Models\Configuracion::where('clave', 'total_horas_1')->value('valor');
        $horas2 = \App\Models\Configuracion::where('clave', 'total_horas_2')->value('valor');

        if (!$horas1 && !$horas2) {
            return redirect()->back()->with('error', 'No se han configurado las horas globales.');
        }

        $convenios = Convenio::with('alumno.curso')->get();
        $count = 0;

        foreach ($convenios as $convenio) {
            /** @var \App\Models\Convenio $convenio */
            $updated = false;
            
            if ($convenio->alumno && $convenio->alumno->curso) {
                if (str_contains($convenio->alumno->curso->nombre, '1º')) {
                    $convenio->total_horas = $horas1;
                    $updated = true;
                } elseif (str_contains($convenio->alumno->curso->nombre, '2º')) {
                    $convenio->total_horas = $horas2;
                    $updated = true;
                }
            }

            if ($updated) {
                $convenio->save();
                $count++;
            }
        }

        return redirect()->back()->with('success', "Se han actualizado las horas de {$count} convenios.");
    }
}
