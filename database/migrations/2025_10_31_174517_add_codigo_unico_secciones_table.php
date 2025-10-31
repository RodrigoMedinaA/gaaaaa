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
        Schema::table('seccions', function (Blueprint $table) {
            $table->string('codigo')
                  ->unique() // Asegura que no se repita
                  ->after('id'); // Opcional: para la posición
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seccions', function (Blueprint $table) {
            $table->dropColumn('codigo');
        });
    }
};
