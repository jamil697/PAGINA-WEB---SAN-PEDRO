<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Solicitud de intención de misa enviada por un feligrés
 * a través del formulario público.
 */
class IntencionMisa extends Model
{
    use HasFactory;

    protected $table = 'intenciones_misa';

    protected $fillable = [
        'nombre_solicitante',
        'email',
        'telefono',
        'intencion',
        'fecha_misa',
        'misa_id',
        'estado_leido',
    ];

    protected $casts = [
        'fecha_misa' => 'date',
        'estado_leido' => 'boolean',
    ];

    public function misa(): BelongsTo
    {
        return $this->belongsTo(Misa::class);
    }
}
