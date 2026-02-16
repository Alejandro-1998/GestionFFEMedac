<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Convenio;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Empresa;
use App\Models\CursoAcademico;

class ConvenioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alumno = Alumno::where('dni', '12345678Z')->first(); 
        $profesor = User::where('email', 'profe@fct.com')->first();
        $cursosActivos = CursoAcademico::all();
        
        $alumnosRandom = Alumno::where('dni', '!=', '12345678Z')->get();
        $empresas = Empresa::with(['sedes', 'empleados'])->get();

        if ($empresas->isEmpty() || $cursosActivos->isEmpty()) {
            return;
        }

        $empresaAleatoria = $empresas->random();
        
        $sedeDeEsaEmpresa = $empresaAleatoria->sedes->first();
        $empleadoDeEsaEmpresa = $empresaAleatoria->empleados->first();

        if ($alumno && $profesor && $sedeDeEsaEmpresa && $empleadoDeEsaEmpresa) {
            Convenio::create([
                'alumno_id' => $alumno->id,
                'profesor_id' => $profesor->id,
                'empresa_id' => $empresaAleatoria->id,
                'sede_id' => $sedeDeEsaEmpresa->id,
                'empleado_id' => $empleadoDeEsaEmpresa->id,
                'curso_academico_id' => $cursosActivos->random()->id,
                'total_horas' => 370,
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(3),
                'estado' => 'en_proceso',
            ]);
        }

        foreach($alumnosRandom as $alum) {
            $emp = $empresas->random();
            $sede = $emp->sedes->isNotEmpty() ? $emp->sedes->random() : null;
            $empleado = $emp->empleados->isNotEmpty() ? $emp->empleados->random() : null;

            if ($profesor && $sede && $empleado && rand(0, 1) === 1) {
                Convenio::create([
                    'alumno_id' => $alum->id,
                    'profesor_id' => $profesor->id,
                    'empresa_id' => $emp->id,
                    'sede_id' => $sede->id,
                    'empleado_id' => $empleado->id,
                    'curso_academico_id' => $cursosActivos->random()->id,
                    'total_horas' => 370,
                    'fecha_inicio' => now()->addDays(rand(1, 10)),
                    'fecha_fin' => now()->addMonths(3),
                    'estado' => 'en_proceso',
                ]);
            } else {
                Convenio::create([
                    'alumno_id' => $alum->id,
                    'profesor_id' => null,
                    'empresa_id' => $emp->id,
                    'sede_id' => null,
                    'empleado_id' => null,
                    'curso_academico_id' => $cursosActivos->random()->id,
                    'total_horas' => null,
                    'fecha_inicio' => null,
                    'fecha_fin' => null,
                    'estado' => 'asignada',
                ]);
            }
        }
    }
}
