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
        Schema::create('seccions', function (Blueprint $table) {
            $table->id();
            $table->string('modulo'); // enum
            $table->string('nombre');
            $table->foreignId('docente_id')->constrained('docentes')->deleteOnCascade();
            $table->string('modalidad'); // enum
            $table->json('dias_estudio')->nullable(); // ¿array?
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('turno'); // enum
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seccions');
    }
};
