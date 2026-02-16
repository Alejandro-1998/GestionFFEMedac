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
        // 1. Create Ciclos table
        Schema::create('ciclos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // e.g., DAW, Marketing
            $table->timestamps();
        });

        // 2. Add ciclo_id to cursos_academicos
        Schema::table('cursos_academicos', function (Blueprint $table) {
            $table->foreignId('ciclo_id')->nullable()->constrained('ciclos')->onDelete('set null');
        });

        // 3. Create pivot table for Companies and Ciclos
        Schema::create('ciclo_empresa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('ciclo_id')->constrained('ciclos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciclo_empresa');
        
        Schema::table('cursos_academicos', function (Blueprint $table) {
            $table->dropForeign(['ciclo_id']);
            $table->dropColumn('ciclo_id');
        });

        Schema::dropIfExists('ciclos');
    }
};
