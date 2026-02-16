<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CursoAcademico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfesorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profesores = User::where('rol', 'profesor')->paginate(10);
        return view('profesores.index', compact('profesores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cursos = CursoAcademico::all();
        return view('profesores.create', compact('cursos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'cursos' => ['array'],
            'cursos.*' => ['exists:cursos_academicos,id'],
        ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'profesor',
        ]);

        if ($request->has('cursos')) {
            $user->cursos()->sync($request->cursos);
        }

        return redirect()->route('profesores.index')->with('success', 'Profesor creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used currently
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $profesor = User::findOrFail($id);
        $cursos = CursoAcademico::all();
        // Load the assigned course IDs
        $cursosAsignados = $profesor->cursos->pluck('id')->toArray();
        
        return view('profesores.edit', compact('profesor', 'cursos', 'cursosAsignados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $profesor = User::findOrFail($id);

        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($profesor->id)],
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'cursos' => ['array'],
            'cursos.*' => ['exists:cursos_academicos,id'],
        ]);

        $profesor->nombre = $request->nombre;
        $profesor->email = $request->email;
        if ($request->filled('password')) {
            $profesor->password = Hash::make($request->password);
        }
        $profesor->save();

        if ($request->has('cursos')) {
            $profesor->cursos()->sync($request->cursos);
        } else {
            $profesor->cursos()->detach();
        }

        return redirect()->route('profesores.index')->with('success', 'Profesor actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
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
