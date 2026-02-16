<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Alumno;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'nombre' => 'Admin Principal',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'rol' => 'admin',
        ]);

        $profesor = User::factory()->create([
            'nombre' => 'Profesor Tutor',
            'email' => 'profe@fct.com',
            'password' => bcrypt('password'),
            'rol' => 'profesor',
        ]);

        // Asociar al profesor a los cursos de DAW
        $cursosDaw = \App\Models\CursoAcademico::whereHas('ciclo', function ($query) {
            $query->where('nombre', 'DAW');
        })->get();

        $profesor->cursos()->attach($cursosDaw);
    }
}
