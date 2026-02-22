<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Modulo;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresa1 = Empresa::create([
            'nombre' => 'ABP Tecnológica',
            'email' => 'abp@tecnologica.com',
            'telefono' => '+34 618 973 197',
            'direccion' => 'Astrónoma Cecilia Payne S/N, Edificio Centauro M 2.5',
            'nif' => 'A12345678',
        ]);

        $empresa2 = Empresa::create([
            'nombre' => 'Nogomet Comunicación',
            'email' => 'info@nogometcomunicacion.com',
            'telefono' => '857 803 925',
            'direccion' => 'C/ Cruz Conde, 19, Oficina 1',
            'nif' => 'B87654321',
        ]);

        // Obtener módulos
        $moduloDAW       = Modulo::where('nombre', 'Desarrollo de Aplicaciones Web')->first();
        $moduloMarketing = Modulo::where('nombre', 'Marketing y Publicidad')->first();

        $empresa1->modulos()->attach($moduloDAW->id);
        $empresa2->modulos()->attach($moduloDAW->id);
        $empresa2->modulos()->attach($moduloMarketing->id);
    }
}
