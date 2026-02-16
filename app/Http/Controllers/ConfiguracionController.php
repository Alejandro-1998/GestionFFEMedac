<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $horas1 = Configuracion::firstOrCreate(['clave' => 'total_horas_1'], ['valor' => 0]);
        $horas2 = Configuracion::firstOrCreate(['clave' => 'total_horas_2'], ['valor' => 0]);

        return response()->json([
            'total_horas_1' => $horas1->valor,
            'total_horas_2' => $horas2->valor,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'total_horas_1' => 'required|integer|min:0',
            'total_horas_2' => 'required|integer|min:0',
        ]);

        Configuracion::updateOrCreate(['clave' => 'total_horas_1'], ['valor' => $request->total_horas_1]);
        Configuracion::updateOrCreate(['clave' => 'total_horas_2'], ['valor' => $request->total_horas_2]);

        return response()->json(['message' => 'Configuración actualizada correctamente.']);
    }
}
