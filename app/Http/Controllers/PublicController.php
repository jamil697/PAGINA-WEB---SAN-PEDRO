<?php

namespace App\Http\Controllers;

use App\Models\AvisoParroquial;
use App\Models\Misa;

/**
 * Controlador del portal público: página de inicio y vista de escaneo.
 */
class PublicController extends Controller
{
    /**
     * Portal informativo: historia, misión, visión, horarios y avisos.
     */
    public function home()
    {
        $misas = Misa::where('activo', true)
            ->orderBy('dia_semana')
            ->orderBy('hora')
            ->get()
            ->groupBy('dia_semana_texto');

        $avisos = AvisoParroquial::activos()->take(6)->get();

        return view('public.home', compact('misas', 'avisos'));
    }

    /**
     * Vista de escaneo móvil: activa la cámara y ejecuta el modelo
     * de visión artificial en el navegador (TensorFlow.js).
     */
    public function escaneo()
    {
        return view('public.escaneo');
    }
}
