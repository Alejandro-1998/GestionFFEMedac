<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Modulo;

use Illuminate\Validation\Rule;

class EmpleadoController extends Controller
{
    /**
     * Listado de Empleados.
     */
    public function index(Request $request)
    {
        $query = Empleado::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('apellido2', 'like', "%{$search}%")
                  ->orWhere('dni_pasaporte', 'like', "%{$search}%")
                  ->orWhere('cargo', 'like', "%{$search}%")
                  ->orWhereHas('empresa', function($q) use ($search) {
                      $q->where('nombre', 'like', "%{$search}%");
                  });
        }

        $empleados = $query->with(['empresa', 'sede', 'modulos'])->get();
        $modulos = Modulo::all();
        
        if ($request->ajax()) {
            return view('empleados.partials.table-rows', compact('empleados', 'modulos'));
        }

        return view("empleados.index", compact("empleados", "modulos"));
    }

    /**
     * Guarda un Empleado.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dni_pasaporte' => 'required',
            'cargo' => 'required',
            'nombre' => 'required',
            'apellido' => 'required',
            'apellido2' => 'required',
            'email' => 'nullable|email',
            'telefono_responsable_laboral' => 'required',
            'sede_id' => [
                'nullable',
                Rule::exists('sedes', 'id')->where(function ($query) use ($request) {
                    return $query->where('empresa_id', $request->empresa_id);
                }),
            ],
        ]);

        $empleado = Empleado::create($request->all());

        if ($request->has('modulos')) {
            $empleado->modulos()->sync($request->modulos);
        } else {
            $empleado->modulos()->detach();
        }

        return redirect()->route('empresas.show', $empleado->empresa_id)->with('success', 'Empleado creado exitosamente.');
    }

    /**
     * Muestra un Empleado.
     */
    public function show(string $id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('empleados.show', compact('empleado'));
    }

    /**
     * Actualiza un Empleado.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'dni_pasaporte' => 'required',
            'cargo' => 'required',
            'nombre' => 'required',
            'apellido' => 'required',
            'apellido2' => 'required',
            'email' => 'nullable|email',
            'telefono_responsable_laboral' => 'required',
            'sede_id' => [
                'nullable',
                Rule::exists('sedes', 'id')->where(function ($query) use ($request) {
                    return $query->where('empresa_id', $request->empresa_id);
                }),
            ],
        ]);

        $empleado = Empleado::find($id);
        $empleado->update($request->all());

        if ($request->has('modulos')) {
            $empleado->modulos()->sync($request->modulos);
        } else {
            $empleado->modulos()->detach();
        }

        return back()->with('success', 'Empleado actualizado exitosamente.');
    }

    /**
     * Elimina un Empleado.
     */
    public function destroy(string $id)
    {
        $empleado = Empleado::find($id);
        $empleado->delete();

        return back()->with('success', 'Empleado eliminado exitosamente.');
    }
}
