<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    /**
     * Listado de la Empresas.
     */
    public function index(Request $request)
    {
        $query = Empresa::query();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->can('admin')) {
            $query->whereHas('ciclos', function ($q) use ($user) {
                $q->whereHas('cursos', function ($q2) use ($user) {
                    $q2->whereHas('profesores', function ($q3) use ($user) {
                        $q3->where('user_id', $user->id);
                    });
                });
            });
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('cif', 'like', "%{$search}%")
                  ->orWhere('direccion', 'like', "%{$search}%");
            });
        }

        $empresas = $query->get();
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->can('admin')) {
            $ciclos = \App\Models\Ciclo::all();
        } else {
             $ciclos = \App\Models\Ciclo::whereHas('cursos', function ($query) use ($user) {
                $query->whereHas('profesores', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })->get();
        }

        if ($request->ajax()) {
            return view('empresas.partials.table-rows', compact('empresas', 'ciclos'));
        }

        return view("empresas.index", compact("empresas", "ciclos"));
    }

    /**
     * Muestra el formulario de creación de Empresas.
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->can('admin')) {
            $ciclos = \App\Models\Ciclo::all();
        } else {
            // Get cycles associated with the professor's courses
            $ciclos = \App\Models\Ciclo::whereHas('cursos', function ($query) use ($user) {
                $query->whereHas('profesores', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })->get();
        }

        return view("empresas.create", compact('ciclos'));
    }

    /**
     * Guarda una Empresa.
     */
    /**
     * Guarda una Empresa.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cif' => 'required|string|max:20|unique:empresas',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'ciclos' => 'required|array|min:1',
            'ciclos.*' => 'exists:ciclos,id',
        ], [
            'ciclos.required' => 'Debes seleccionar al menos un ciclo formativo.',
            'ciclos.min' => 'Debes seleccionar al menos un ciclo formativo.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validate that the user can assign these cycles
        if (!$user->can('admin') && $request->has('ciclos')) {
             $allowedCiclosIds = \App\Models\Ciclo::whereHas('cursos', function ($query) use ($user) {
                $query->whereHas('profesores', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })->pluck('id')->toArray();

            foreach ($request->ciclos as $cicloId) {
                if (!in_array($cicloId, $allowedCiclosIds)) {
                    abort(403, 'No tienes permiso para asignar este ciclo.');
                }
            }
        }

        $empresa = Empresa::create($validated);

        if ($request->has('ciclos')) {
            $empresa->ciclos()->sync($request->ciclos);
            
            // Auto-associate courses belonging to these cycles
            $cursosIds = \App\Models\CursoAcademico::whereIn('ciclo_id', $request->ciclos)->pluck('id')->toArray();
            $empresa->cursos()->sync($cursosIds);
        }

        return redirect()->route('empresas.index');
    }

    /**
     * Muestra una Empresa.
     */
    public function show(string $id)
    {
        $empresa = Empresa::with(['sedes', 'empleados', 'cursos', 'ciclos'])->findOrFail($id);
        
        $ciclosAsociados = $empresa->ciclos->pluck('nombre');

        return view("empresas.show", compact("empresa", "ciclosAsociados"));
    }

    /**
     * Actualiza un Empresa.
     */
    public function update(Request $request, Empresa $empresa)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cif' => 'required|string|max:20|unique:empresas,cif,' . $empresa->id,
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
        ]);

        $empresa->update($validated);

        // Ciclos are not editable during update, so we don't sync/detach them.

        return redirect()->route('empresas.index');
    }

    /**
     * Elimina un Empresa.
     */
    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return redirect()->route('empresas.index');
    }
}
