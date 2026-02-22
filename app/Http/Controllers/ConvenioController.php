<?php

namespace App\Http\Controllers;

use App\Models\Convenio;
use App\Models\User;
use App\Models\Empresa;
use App\Models\CursoAcademico;
use App\Models\Modulo;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Alumno;
use App\Models\Configuracion;

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

        if ($request->has('curso_academico_id') && $request->curso_academico_id != '') $query->where('curso_academico_id', $request->curso_academico_id);
            
        if ($request->filled('estado')) {
            $estado = $request->estado;
            $hoy = now()->toDateString();

            if ($estado === 'cancelada') {
                $query->where('estado', 'cancelada');
            } elseif ($estado === 'asignada') {
                $query->where('estado', '!=', 'cancelada')
                      ->where(function ($q) use ($hoy) {
                          $q->whereNull('fecha_inicio')
                            ->orWhere('fecha_inicio', '>', $hoy);
                      });
            } elseif ($estado === 'en_proceso') {
                $query->where('estado', '!=', 'cancelada')
                      ->where('fecha_inicio', '<=', $hoy)
                      ->where(function ($q) use ($hoy) {
                          $q->whereNull('fecha_fin')
                            ->orWhere('fecha_fin', '>=', $hoy);
                      });
            } elseif ($estado === 'finalizada') {
                $query->where('estado', '!=', 'cancelada')
                      ->whereNotNull('fecha_fin')
                      ->where('fecha_fin', '<', $hoy);
            }
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
        /** @var \App\Models\User $usuario */
        $usuario = Auth::user();
        $cursoActual = CursoAcademico::where('actual', true)->first();

        if ($usuario->rol === 'admin') {
            $modulos = Modulo::with('cursos')->get();
            $empresas = Empresa::orderBy('nombre')->get();
        } else {
            $modulos = $usuario->modulos()->with('cursos')->get();
            $modulosIds = $modulos->pluck('id');
            $empresas = Empresa::whereHas('modulos', function ($q) use ($modulosIds) {
                $q->whereIn('modulos.id', $modulosIds);
            })->orderBy('nombre')->get();
        }

        $modulosIds = $modulos->pluck('id');
        $cursosIds = Curso::whereIn('modulo_id', $modulosIds)->pluck('id');

        $alumnos = Alumno::whereIn('curso_id', $cursosIds)
            ->when($cursoActual, fn($q) => $q->where('curso_academico_id', $cursoActual->id))
            ->whereDoesntHave('convenios')
            ->with('curso.modulo')
            ->get()
            ->map(fn($a) => [
                'id'              => $a->id,
                'nombre_completo' => $a->nombre_completo,
                'curso_id'        => $a->curso_id,
                'modulo_id'       => $a->curso?->modulo_id,
            ]);

        $modulosJson = $modulos->map(fn($m) => [
            'id'     => $m->id,
            'nombre' => $m->nombre,
            'cursos' => $m->cursos->map(fn($c) => ['id' => $c->id, 'nombre' => $c->nombre]),
        ]);

        $profesores = User::where('rol', 'profesor')->get();

        return view('convenios.create', compact('alumnos', 'profesores', 'empresas', 'modulosJson'));
    }

    /**
     * Crear Convenio
     */
    public function store(Request $request)
    {
        $request->validate([
            'alumno_id'  => 'required|exists:alumnos,id|unique:convenios,alumno_id',
            'empresa_id' => 'required|exists:empresas,id',
        ], [
            'alumno_id.required'  => 'Debes seleccionar un alumno.',
            'alumno_id.exists'    => 'El alumno seleccionado no es válido.',
            'alumno_id.unique'    => 'Este alumno ya tiene un convenio asignado.',
            'empresa_id.required' => 'Debes seleccionar una empresa.',
            'empresa_id.exists'   => 'La empresa seleccionada no es válida.',
        ]);

        $alumno = Alumno::findOrFail($request->alumno_id);

        /** @var \App\Models\User $usuario */
        $usuario = Auth::user();

        Convenio::create([
            'alumno_id'          => $alumno->id,
            'curso_academico_id' => $alumno->curso_academico_id,
            'empresa_id'         => $request->empresa_id,
            'profesor_id'        => $usuario->rol === 'profesor' ? $usuario->id : null,
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

    public function cancelar(Convenio $convenio)
    {
        $convenio->update(['estado' => 'cancelada']);
        return redirect()->route('convenios.show', $convenio->id)->with('success', 'El convenio ha sido cancelado.');
    }

    public function establecerHoras()
    {
        /** @var \App\Models\User $usuario */
        $usuario = Auth::user();

        $horas1        = Configuracion::where('clave', 'total_horas_1')->value('valor');
        $horas2        = Configuracion::where('clave', 'total_horas_2')->value('valor');
        $fechaInicio1  = Configuracion::where('clave', 'fecha_inicio_1')->value('valor');
        $fechaFin1     = Configuracion::where('clave', 'fecha_fin_1')->value('valor');
        $fechaInicio2  = Configuracion::where('clave', 'fecha_inicio_2')->value('valor');
        $fechaFin2     = Configuracion::where('clave', 'fecha_fin_2')->value('valor');

        if (!$horas1 && !$horas2) return redirect()->back()->with('error', 'No se han configurado las horas globales.');

        $query = Convenio::with('alumno.curso.modulo');

        if ($usuario->rol === 'profesor') {
            $modulosIds = $usuario->modulos()->pluck('modulos.id');
            $query->whereHas('alumno.curso', function ($q) use ($modulosIds) {
                $q->whereIn('modulo_id', $modulosIds);
            });
        }

        $convenios = $query->get();
        $count = 0;

        foreach ($convenios as $convenio) {
            /** @var \App\Models\Convenio $convenio */
            $updated = false;

            if ($convenio->alumno && $convenio->alumno->curso) {
                $es1 = str_contains($convenio->alumno->curso->nombre, '1º');
                $es2 = str_contains($convenio->alumno->curso->nombre, '2º');

                if ($es1) {
                    $convenio->total_horas = $horas1;
                    if ($fechaInicio1) $convenio->fecha_inicio = $fechaInicio1;
                    if ($fechaFin1)    $convenio->fecha_fin    = $fechaFin1;
                    $updated = true;
                } elseif ($es2) {
                    $convenio->total_horas = $horas2;
                    if ($fechaInicio2) $convenio->fecha_inicio = $fechaInicio2;
                    if ($fechaFin2)    $convenio->fecha_fin    = $fechaFin2;
                    $updated = true;
                }
            }

            if ($updated) {
                $convenio->save();
                $count++;
            }
        }

        return redirect()->back()->with('success', "Se han actualizado las horas y fechas de {$count} convenios.");
    }
}
