<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Representa un horario recurrente de misa, confesión o catequesis.
 */
class Misa extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_actividad',
        'dia_semana',
        'hora',
        'observaciones',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'hora' => 'datetime:H:i',
    ];

    /** Nombres legibles de los días de la semana (0 = Domingo). */
    public const DIAS_SEMANA = [
        0 => 'Domingo',
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
    ];

    public function getDiaSemanaTextoAttribute(): string
    {
        return self::DIAS_SEMANA[$this->dia_semana] ?? 'N/D';
    }

    public function intenciones(): HasMany
    {
        return $this->hasMany(IntencionMisa::class);
    }
}
