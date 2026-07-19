<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IntencionMisa;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Panel de gestión de las intenciones de misa enviadas por los feligreses
 * mediante el formulario público.
 */
class IntencionAdminController extends Controller
{
    public function index(): View
    {
        $intenciones = IntencionMisa::with('misa')
            ->orderBy('estado_leido')
            ->latest('fecha_misa')
            ->paginate(15);

        return view('admin.intenciones.index', compact('intenciones'));
    }

    public function marcarLeido(IntencionMisa $intencion): RedirectResponse
    {
        $intencion->update(['estado_leido' => true]);

        return back()->with('exito', 'Intención marcada como leída.');
    }

    public function destroy(IntencionMisa $intencion): RedirectResponse
    {
        $intencion->delete();

        return back()->with('exito', 'Intención eliminada.');
    }
}
