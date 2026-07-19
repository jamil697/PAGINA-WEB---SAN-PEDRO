<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Aviso o evento parroquial publicado desde el panel administrativo
 * (noticias, festividades, campañas comunitarias).
 */
class AvisoParroquial extends Model
{
    use HasFactory;

    protected $table = 'avisos_parroquiales';

    protected $fillable = [
        'titulo',
        'contenido',
        'fecha_publicacion',
        'fecha_evento',
        'imagen_path',
        'user_id',
        'activo',
    ];

    protected $casts = [
        'fecha_publicacion' => 'date',
        'fecha_evento' => 'date',
        'activo' => 'boolean',
    ];

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true)
            ->orderByDesc('fecha_publicacion');
    }
}
