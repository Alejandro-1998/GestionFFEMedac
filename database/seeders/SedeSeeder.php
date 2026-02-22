<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;
use App\Models\Sede;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener Empresa
        $empresa1 = Empresa::where('nombre', 'ABP Tecnológica')->first();

        $sede = Sede::create([
            'empresa_id' => $empresa1->id,
            'nombre' => 'Parque Tecnológico',
            'ubicacion' => 'Córdoba',
            'direccion' => 'Astrónoma Cecilia Payne S/N, Edificio Centauro M 2.5',
            'telefono' => '+34 618 973 197',
        ]);
    }
}
