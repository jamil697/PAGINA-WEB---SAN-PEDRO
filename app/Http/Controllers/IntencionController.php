<?php

namespace App\Http\Controllers;

use App\Models\IntencionMisa;
use App\Models\Misa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Gestiona el formulario público mediante el cual los feligreses
 * envían sus intenciones de misa.
 */
class IntencionController extends Controller
{
    public function create(): View
    {
        $misas = Misa::where('activo', true)->get();

        return view('public.intenciones', compact('misas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre_solicitante' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'intencion' => ['required', 'string', 'max:1000'],
            'fecha_misa' => ['required', 'date', 'after_or_equal:today'],
            'misa_id' => ['nullable', 'exists:misas,id'],
        ]);

        IntencionMisa::create($datos);

        return redirect()
            ->route('intenciones.create')
            ->with('exito', 'Tu intención de misa fue enviada correctamente. ¡Gracias!');
    }
}
