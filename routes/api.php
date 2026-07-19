<?php

/*
|--------------------------------------------------------------------------
| RUTAS API — Iglesia San Pedro de Huánuco
|--------------------------------------------------------------------------
| Agrega este bloque a tu routes/api.php existente.
| Esta ruta es consumida por public/js/escaneo.js (fetch asíncrono)
| cada vez que el modelo de TensorFlow.js reconoce un bien cultural.
*/

use App\Http\Controllers\Api\BienCulturalController;
use Illuminate\Support\Facades\Route;

Route::post('/bienes-culturales/buscar', [BienCulturalController::class, 'buscarPorLabel'])
    ->name('api.bienes-culturales.buscar');
