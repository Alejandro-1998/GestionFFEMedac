<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleado_modulo', function (Blueprint $table) {
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->foreignId('modulo_id')->constrained('modulos')->onDelete('cascade');
            $table->primary(['empleado_id', 'modulo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleado_modulo');
    }
};
