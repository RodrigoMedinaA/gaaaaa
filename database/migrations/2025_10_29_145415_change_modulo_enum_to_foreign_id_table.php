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
            $table->dropColumn('modulo');
            $table->foreignId('modulo_id')->after('id')->constrained('modulos')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seccions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('modulo_id');
            $table->string('modulo')->after('id')->nullable();
        });
    }
};
