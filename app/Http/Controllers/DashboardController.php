<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Sede;
use App\Models\Empleado;
use App\Models\RegistroActividad;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'alumnos' => \App\Models\Alumno::count(),
            'empresas' => Empresa::count(),
            'sedes' => Sede::count(),
            'empleados' => Empleado::count(),
        ];

        $actividades = RegistroActividad::with('sujeto')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'actividades'));
    }
}
