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
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->string('genero', 20)->nullable()->change();
            $table->string('estado_civil', 20)->nullable()->change();
            $table->date('fecha_nacimiento')->nullable()->change();
            $table->string('telefono', 20)->nullable()->change();
            $table->string('email', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->string('genero', 20)->nullable(false)->change();
            $table->string('estado_civil', 20)->nullable(false)->change();
            $table->date('fecha_nacimiento')->nullable(false)->change();
            $table->string('telefono', 20)->nullable(false)->change();
            $table->string('email', 100)->unique()->nullable(false)->change();
        });
    }
};
