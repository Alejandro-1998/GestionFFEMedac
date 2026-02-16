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
        Schema::create('memorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId("convenio_id")->constrained('convenios')->onDelete('cascade');
            $table->date('fecha');
            $table->decimal('horas');
            $table->text('actividad');
            $table->boolean('aprobado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memorias');
    }
};
