<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BienCultural;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Expone el endpoint que consume el script de reconocimiento de imágenes
 * (public/js/escaneo.js) ejecutándose en el celular del visitante.
 */
class BienCulturalController extends Controller
{
    /**
     * Recibe la etiqueta ("label") detectada por el modelo de TensorFlow.js
     * y devuelve la información histórica e iconográfica del bien cultural
     * correspondiente.
     *
     * POST /api/bienes-culturales/buscar
     * Body: { "label": "san_pedro_lienzo_01" }
     */
    public function buscarPorLabel(Request $request): JsonResponse
    {
        $validado = $request->validate([
            'label' => ['required', 'string', 'max:191'],
        ]);

        $bien = BienCultural::activos()
            ->where('label_ia', $validado['label'])
            ->first();

        if (! $bien) {
            return response()->json([
                'encontrado' => false,
                'mensaje' => 'No se encontró información registrada para este elemento.',
            ], 404);
        }

        return response()->json([
            'encontrado' => true,
            'data' => [
                'id' => $bien->id,
                'nombre' => $bien->nombre,
                'autor' => $bien->autor,
                'siglo' => $bien->siglo,
                'resena_historica' => $bien->resena_historica,
                'iconografia' => $bien->iconografia,
                'imagen_url' => $bien->imagen_url,
            ],
        ]);
    }
}
