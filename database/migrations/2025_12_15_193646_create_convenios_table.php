<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('convenios', function (Blueprint $table) {
            $table->id();
            $table->foreignId("alumno_id")->constrained('alumnos')->onDelete('cascade');
            $table->foreignId("profesor_id")->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId("empresa_id")->constrained('empresas')->onDelete('cascade');
            $table->foreignId("empleado_id")->nullable()->constrained('empleados')->onDelete('cascade');
            $table->foreignId("curso_academico_id")->constrained('cursos_academicos')->onDelete('cascade');
            $table->foreignId("sede_id")->nullable()->constrained('sedes')->onDelete('cascade');
            $table->integer("total_horas")->nullable();
            $table->date("fecha_inicio")->nullable();
            $table->date("fecha_fin")->nullable();
            $table->enum("estado", ['asignada', 'en_proceso', 'finalizada', 'cancelada'])->default('en_proceso');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convenios');
    }
};
