<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfesorController extends Controller
{
    public function index()
    {
        $profesores = User::where('rol', 'profesor')->with('modulos')->paginate(10);
        return view('profesores.index', compact('profesores'));
    }

    public function create()
    {
        $modulos = Modulo::all();
        return view('profesores.create', compact('modulos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'modulos' => ['array'],
            'modulos.*' => ['exists:modulos,id'],
        ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'profesor',
        ]);

        if ($request->has('modulos')) {
            $user->modulos()->sync($request->modulos);
        }

        return redirect()->route('profesores.index')->with('success', 'Profesor creado correctamente.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $profesor = User::with('modulos')->findOrFail($id);
        $modulos = Modulo::all();
        $modulosAsignados = $profesor->modulos->pluck('id')->toArray();

        return view('profesores.edit', compact('profesor', 'modulos', 'modulosAsignados'));
    }

    public function update(Request $request, string $id)
    {
        $profesor = User::findOrFail($id);

        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($profesor->id)],
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'modulos' => ['array'],
            'modulos.*' => ['exists:modulos,id'],
        ]);

        $profesor->nombre = $request->nombre;
        $profesor->email = $request->email;
        if ($request->filled('password')) {
            $profesor->password = Hash::make($request->password);
        }
        $profesor->save();

        if ($request->has('modulos')) {
            $profesor->modulos()->sync($request->modulos);
        } else {
            $profesor->modulos()->detach();
        }

        return redirect()->route('profesores.index')->with('success', 'Profesor actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $profesor = User::findOrFail($id);
        if ($profesor->rol !== 'profesor') {
             return redirect()->route('profesores.index')->with('error', 'No puedes eliminar este usuario.');
        }

        $profesor->delete();
        return redirect()->route('profesores.index')->with('success', 'Profesor eliminado correctamente.');
    }
}
