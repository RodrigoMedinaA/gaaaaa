<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            // Cambia el tipo de la columna a TEXT (sin límite de longitud)
            $table->text('tipo_documento')->change();
        });
    }

    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            // La revierte a un string de 20
            $table->string('tipo_documento', 20)->change();
        });
    }
};
