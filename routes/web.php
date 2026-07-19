<?php

/*
|--------------------------------------------------------------------------
| RUTAS — Iglesia San Pedro de Huánuco
|--------------------------------------------------------------------------
| Agrega este bloque a tu routes/web.php existente (después del `use`
| de los controladores). No reemplaces el archivo completo: conserva
| las rutas de autenticación que genera Laravel Breeze (auth.php).
*/

use App\Http\Controllers\Admin\AvisoParroquialController;
use App\Http\Controllers\Admin\BienCulturalAdminController;
use App\Http\Controllers\Admin\IntencionAdminController;
use App\Http\Controllers\Admin\MisaController;
use App\Http\Controllers\IntencionController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Módulo público
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/escaneo', [PublicController::class, 'escaneo'])->name('escaneo');

Route::get('/intenciones-de-misa', [IntencionController::class, 'create'])->name('intenciones.create');
Route::post('/intenciones-de-misa', [IntencionController::class, 'store'])->name('intenciones.store');

/*
|--------------------------------------------------------------------------
| Módulo administrativo (protegido por autenticación de Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource('bienes-culturales', BienCulturalAdminController::class);
        Route::resource('misas', MisaController::class);
        Route::resource('avisos', AvisoParroquialController::class);

        Route::get('intenciones', [IntencionAdminController::class, 'index'])->name('intenciones.index');
        Route::patch('intenciones/{intencion}/marcar-leido', [IntencionAdminController::class, 'marcarLeido'])
            ->name('intenciones.marcar-leido');
        Route::delete('intenciones/{intencion}', [IntencionAdminController::class, 'destroy'])
            ->name('intenciones.destroy');
    });

require __DIR__ . '/auth.php'; // generado por Laravel Breeze
