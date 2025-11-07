<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno')->nullable();
            $table->string('correo')->unique();
            $table->string('celular', 20)->nullable();

            // Guardamos el valor del enum como string
            $table->string('tipo_documento', 50);
            $table->string('num_documento', 30);

            $table->timestamps();

            $table->unique(['tipo_documento', 'num_documento'], 'empleados_documento_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};


