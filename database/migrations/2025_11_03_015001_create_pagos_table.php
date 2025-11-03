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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->string('codigo')->unique();
            $table->foreignId('matricula_id')
                  ->constrained('matriculas')
                  ->cascadeOnDelete(); // Si se borra la matrícula, se borran sus pagos

            $table->decimal('monto', 6, 2); // 1000.00
            $table->string('estado')->default('pagado'); // pagado, pendiente, anulado
            $table->date('fecha_vencimiento');
            $table->string('metodo_pago')->nullable(); // Yape, Efectivo, etc.
            $table->date('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
