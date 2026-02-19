<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CursoAcademico;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin = User::create([
            'nombre' => 'Sergio Díaz Morales',
            'email' => 'sergio.diaz@doc.medac.es',
            'password' => bcrypt('password'),
            'rol' => 'admin',
        ]);

        $profesor = User::create([
            'nombre' => 'Javier Ruiz Jurado',
            'email' => 'javier.ruiz@doc.medac.es',
            'password' => bcrypt('password'),
            'rol' => 'profesor',
        ]);

        // Asociar al profesor a los cursos de DAW
        $cursosDaw = CursoAcademico::whereHas('ciclo', function ($query) {
            
            $query->where('nombre', 'DAW');
        })->get();

        $profesor->cursos()->attach($cursosDaw);
    }
}
