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
        // 1. Create Modulos table
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // e.g., DAW, Marketing
            $table->timestamps();
        });

        // 2. Create Cursos table (Groups: 1º, 2º, etc.)
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // 1º, 2º
            $table->foreignId('modulo_id')->constrained('modulos')->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Create pivot table for Companies and Modulos
        Schema::create('empresa_modulo', function (Blueprint $table) {
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('modulo_id')->constrained('modulos')->onDelete('cascade');
            $table->primary(['empresa_id', 'modulo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_modulo');
        Schema::dropIfExists('cursos');
        Schema::dropIfExists('modulos');
    }
};
