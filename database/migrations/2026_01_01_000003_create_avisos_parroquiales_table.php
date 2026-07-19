<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Avisos y eventos parroquiales publicados por el personal
     * de la iglesia desde el panel administrativo.
     */
    public function up(): void
    {
        Schema::create('avisos_parroquiales', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('contenido');
            $table->date('fecha_publicacion');
            $table->date('fecha_evento')->nullable();
            $table->string('imagen_path')->nullable();

            // Autor/administrador que publicó el aviso.
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avisos_parroquiales');
    }
};
