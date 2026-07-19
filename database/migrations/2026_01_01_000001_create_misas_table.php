<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla que almacena la agenda litúrgica: misas, confesiones y catequesis.
     */
    public function up(): void
    {
        Schema::create('misas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_actividad', 50); // Misa, Confesion, Catequesis, Adoracion
            $table->unsignedTinyInteger('dia_semana'); // 0 = Domingo ... 6 = Sábado
            $table->time('hora');
            $table->string('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('misas');
    }
};
