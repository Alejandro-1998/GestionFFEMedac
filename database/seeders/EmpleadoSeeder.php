<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;
use App\Models\Empresa;
use App\Models\Sede;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener Empresa
        $empresa1 = Empresa::where('nombre', 'ABP Tecnológica')->first();

        // Obtener Sede
        $sede1 = Sede::where('nombre', 'Parque Tecnológico')->first();

        $empleado1 = Empleado::create([
            'empresa_id' => $empresa1->id,
            'sede_id' => $sede1->id,
            'dni_pasaporte' => '12345678A',
            'nombre' => 'Antonio',
            'apellido' => 'Barbado',
            'apellido2' => 'Portillo',
            'email' => 'antonio.barbado@tecnologica.com',
            'fecha_nacimiento' => '1971-10-15',
            'telefono_responsable_laboral' => '600123456',
            'cargo' => 'CEO y Tutor Laboral',
            'activo' => true,
        ]);
    }
}
