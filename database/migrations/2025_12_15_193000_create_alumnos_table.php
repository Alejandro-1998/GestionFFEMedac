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
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->string('dni')->unique();
            $table->string('dni_encriptado');
            $table->string('email')->unique();
            $table->string('nota_1')->nullable();
            $table->string('nota_2')->nullable();
            $table->string('nota_3')->nullable();
            $table->string('nota_4')->nullable();
            $table->string('nota_5')->nullable();
            $table->string('nota_6')->nullable();
            $table->string('nota_7')->nullable();
            $table->string('nota_8')->nullable();
            $table->decimal('nota_media', 4, 2)->nullable();
            $table->foreignId('curso_id')->nullable()->constrained('cursos')->onDelete('set null');
            $table->foreignId('curso_academico_id')->nullable()->constrained('cursos_academicos')->onDelete('set null');
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
