<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Inventario histórico-cultural: santos, lienzos y reliquias.
     * El campo `label_ia` es la llave que conecta cada registro
     * con la clase que el modelo de TensorFlow.js detecta en el celular.
     */
    public function up(): void
    {
        Schema::create('bienes_culturales', function (Blueprint $table) {
            $table->id();

            // Debe coincidir exactamente con el "className" exportado
            // desde Teachable Machine / el modelo de visión artificial.
            $table->string('label_ia')->unique();

            $table->string('nombre');
            $table->string('autor')->nullable();
            $table->string('siglo', 50)->nullable();
            $table->text('resena_historica');
            $table->text('iconografia')->nullable();
            $table->string('imagen_path')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bienes_culturales');
    }
};
