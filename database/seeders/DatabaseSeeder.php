<?php

namespace Database\Seeders;

use Database\Seeders\AlumnoSeeder;
use Database\Seeders\EmpresaSeeder;
use Database\Seeders\SedeSeeder;
use Database\Seeders\EmpleadoSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(CursoAcademicoSeeder::class);
        $this->call(AlumnoSeeder::class);
        $this->call(EmpresaSeeder::class);
        $this->call(SedeSeeder::class);
        $this->call(EmpleadoSeeder::class);
    }
}