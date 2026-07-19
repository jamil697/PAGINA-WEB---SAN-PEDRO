<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Solicitudes de intenciones de misa enviadas por los feligreses
     * mediante el formulario público.
     */
    public function up(): void
    {
        Schema::create('intenciones_misa', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_solicitante');
            $table->string('email')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->text('intencion');
            $table->date('fecha_misa');

            // Relación opcional con el horario de misa elegido.
            $table->foreignId('misa_id')
                ->nullable()
                ->constrained('misas')
                ->nullOnDelete();

            $table->boolean('estado_leido')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intenciones_misa');
    }
};
