<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    /**
     * Listado de las Sedes.
     */
    public function index(Request $request)
    {
        $query = Sede::query();

        if ($request->has('search')) {

            $search = $request->input('search');

            $query->where('nombre', 'like', "%{$search}%")
                  ->orWhere('ubicacion', 'like', "%{$search}%")
                  ->orWhere('direccion', 'like', "%{$search}%")
                  ->orWhereHas('empresa', function($q) use ($search) {
                      $q->where('nombre', 'like', "%{$search}%");
                  });
        }

        $sedes = $query->with('empresa')->get();
        
        if ($request->ajax()) return view('sedes.partials.table-rows', compact('sedes'));

        return view("sedes.index", compact("sedes"));
    }

    /**
     * Muestra una Sede.
     */
    public function show($id)
    {
        $sede = Sede::with(['empresa', 'empleados'])->findOrFail($id);

        return view("sedes.show", compact("sede"));
    }

    /**
     * Guarda una Sede.
     */
    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
        ]);

        Sede::create($request->all());

        return redirect()->back()->with('success', 'Sede creada correctamente.');
    }

    /**
     * Actualiza una Sede.
     */
    public function update(Request $request, Sede $sede)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
        ]);

        $sede->update($request->all());

        return redirect()->back()->with('success', 'Sede actualizada correctamente.');
    }

    /**
     * Elimina una Sede.
     */
    public function destroy(Sede $sede)
    {
        $sede->delete();
        
        return redirect()->back()->with('success', 'Sede eliminada correctamente.');
    }
}
