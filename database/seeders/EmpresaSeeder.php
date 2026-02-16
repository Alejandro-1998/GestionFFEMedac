<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Sede;
use App\Models\Empleado;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear Empresas con sus Sedes y Empleados (asegurando consistencia)
        Empresa::factory(5)->create()->each(function ($empresa) {
            // Cada empresa tiene entre 1 y 3 sedes
            $sedes = Sede::factory(rand(1, 3))->create(['empresa_id' => $empresa->id]);
            
            // Crear empleados para esta empresa, asignándolos a una sede de ESTA empresa
            Empleado::factory(rand(2, 5))->create([
                'empresa_id' => $empresa->id,
                'sede_id' => $sedes->random()->id,
            ]);

            // Asociar a un ciclo aleatorio (ej. DAW)
            $ciclo = \App\Models\Ciclo::inRandomOrder()->first();
            if ($ciclo) {
                $empresa->ciclos()->attach($ciclo->id);

                // Asociar cursos pertenecientes a ese ciclo
                $cursos = \App\Models\CursoAcademico::where('ciclo_id', $ciclo->id)->get();
                $empresa->cursos()->sync($cursos);
            }
        });
    }
}
