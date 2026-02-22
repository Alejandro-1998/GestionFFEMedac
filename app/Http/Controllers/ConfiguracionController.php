<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $claves = [
            'total_horas_1', 'total_horas_2',
            'fecha_inicio_1', 'fecha_fin_1',
            'fecha_inicio_2', 'fecha_fin_2',
        ];

        $config = [];
        foreach ($claves as $clave) {
            $config[$clave] = Configuracion::firstOrCreate(['clave' => $clave], ['valor' => ''])->valor;
        }

        return response()->json($config);
    }

    public function update(Request $request)
    {
        $request->validate([
            'total_horas_1' => 'required|integer|min:0',
            'total_horas_2' => 'required|integer|min:0',
            'fecha_inicio_1' => 'nullable|date',
            'fecha_fin_1'    => 'nullable|date',
            'fecha_inicio_2' => 'nullable|date',
            'fecha_fin_2'    => 'nullable|date',
        ]);

        $claves = [
            'total_horas_1', 'total_horas_2',
            'fecha_inicio_1', 'fecha_fin_1',
            'fecha_inicio_2', 'fecha_fin_2',
        ];

        foreach ($claves as $clave) {
            Configuracion::updateOrCreate(['clave' => $clave], ['valor' => $request->input($clave, '')]);
        }

        return response()->json(['message' => 'Configuración actualizada correctamente.']);
    }
}
