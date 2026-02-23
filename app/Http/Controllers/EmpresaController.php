<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Modulo;
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

        if ($user->rol !== 'admin') {
            // Los profesores solo ven empresas asociadas a sus mismos módulos
            $modulosDelProfesor = $user->modulos->pluck('id');

            $query->whereHas('modulos', function ($q) use ($modulosDelProfesor) {
                $q->whereIn('modulos.id', $modulosDelProfesor);
            });
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('nif', 'like', "%{$search}%")
                  ->orWhere('direccion', 'like', "%{$search}%");
            });
        }

        $empresas = $query->get();
        
        $modulos = Modulo::all();

        if ($request->ajax()) {
            return view('empresas.partials.table-rows', compact('empresas', 'modulos'));
        }

        return view("empresas.index", compact("empresas", "modulos"));
    }

    /**
     * Muestra el formulario de creación de Empresas.
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $modulos = Modulo::all();

        return view("empresas.create", compact('modulos'));
    }

    /**
     * Guarda una Empresa.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'nif' => 'required|string|max:20|unique:empresas',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'modulos' => 'required|array|min:1',
            'modulos.*' => 'exists:modulos,id',
        ], [
            'modulos.required' => 'Debes seleccionar al menos un módulo.',
            'modulos.min' => 'Debes seleccionar al menos un módulo.',
        ]);

        $empresa = Empresa::create($validated);

        if ($request->has('modulos')) {
            $empresa->modulos()->sync($request->modulos);
        }

        return redirect()->route('empresas.index');
    }

    /**
     * Muestra una Empresa.
     */
    public function show(string $id)
    {
        $empresa = Empresa::with(['sedes', 'empleados.modulos', 'modulos'])->findOrFail($id);
        
        $modulosAsociados = $empresa->modulos->pluck('nombre');
        $modulos = Modulo::all();

        return view("empresas.show", compact("empresa", "modulosAsociados", "modulos"));
    }

    /**
     * Actualiza un Empresa.
     */
    public function update(Request $request, Empresa $empresa)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'nif' => 'required|string|max:20|unique:empresas,nif,' . $empresa->id,
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
        ]);

        $empresa->update($validated);

        if ($request->has('modulos')) {
             $empresa->modulos()->sync($request->modulos);
        }

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
