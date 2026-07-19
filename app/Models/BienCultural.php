<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Representa un bien cultural (santo, lienzo, reliquia) de la Iglesia San Pedro.
 * El atributo `label_ia` es la etiqueta que el modelo de visión artificial
 * (TensorFlow.js) devuelve al reconocer la imagen desde la cámara del celular.
 */
class BienCultural extends Model
{
    use HasFactory;

    protected $table = 'bienes_culturales';

    protected $fillable = [
        'label_ia',
        'nombre',
        'autor',
        'siglo',
        'resena_historica',
        'iconografia',
        'imagen_path',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Scope para obtener únicamente los bienes activos y visibles al público.
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Devuelve la URL pública de la imagen almacenada en storage/app/public.
     */
    public function getImagenUrlAttribute(): ?string
    {
        return $this->imagen_path ? asset('storage/' . $this->imagen_path) : null;
    }
}
