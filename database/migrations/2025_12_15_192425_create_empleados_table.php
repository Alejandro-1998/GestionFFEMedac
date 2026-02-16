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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->foreignId("empresa_id")->constrained('empresas')->onDelete('cascade');
            $table->foreignId("sede_id")->constrained('sedes')->onDelete('cascade');
            $table->string('dni_pasaporte');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('apellido2');
            $table->string('email')->nullable();
            $table->string('fecha_nacimiento');
            $table->string('cargo');
            $table->string('telefono_responsable_laboral');
            $table->boolean('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
