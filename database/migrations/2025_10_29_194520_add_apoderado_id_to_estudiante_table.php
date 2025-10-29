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
            $table->foreignId('apoderado_id')
                ->nullable() // Permite que un estudiante NO tenga apoderado
                ->constrained('apoderados') 
                ->nullOnDelete(); // Si se borra el apoderado, esta columna se vuelve NULL
        });
    }

    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropForeign(['apoderado_id']);
            $table->dropColumn('apoderado_id');
        });
    }
};
