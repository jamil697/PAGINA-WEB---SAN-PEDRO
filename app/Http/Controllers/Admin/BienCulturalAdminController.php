<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BienCultural;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * CRUD del inventario histórico-cultural (santos, lienzos, reliquias).
 * Cada registro se asocia a la etiqueta ("label_ia") que el modelo
 * de visión artificial reconoce en el celular.
 */
class BienCulturalAdminController extends Controller
{
    public function index(): View
    {
        $bienes = BienCultural::latest()->paginate(10);

        return view('admin.bienes.index', compact('bienes'));
    }

    public function create(): View
    {
        return view('admin.bienes.form', ['bien' => new BienCultural()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validarDatos($request);

        if ($request->hasFile('imagen')) {
            $datos['imagen_path'] = $request->file('imagen')->store('bienes-culturales', 'public');
        }

        BienCultural::create($datos);

        return redirect()
            ->route('admin.bienes-culturales.index')
            ->with('exito', 'Bien cultural registrado correctamente.');
    }

    public function edit(BienCultural $bienesCultural): View
    {
        return view('admin.bienes.form', ['bien' => $bienesCultural]);
    }

    public function update(Request $request, BienCultural $bienesCultural): RedirectResponse
    {
        $datos = $this->validarDatos($request, $bienesCultural->id);

        if ($request->hasFile('imagen')) {
            if ($bienesCultural->imagen_path) {
                Storage::disk('public')->delete($bienesCultural->imagen_path);
            }
            $datos['imagen_path'] = $request->file('imagen')->store('bienes-culturales', 'public');
        }

        $bienesCultural->update($datos);

        return redirect()
            ->route('admin.bienes-culturales.index')
            ->with('exito', 'Bien cultural actualizado correctamente.');
    }

    public function destroy(BienCultural $bienesCultural): RedirectResponse
    {
        if ($bienesCultural->imagen_path) {
            Storage::disk('public')->delete($bienesCultural->imagen_path);
        }

        $bienesCultural->delete();

        return redirect()
            ->route('admin.bienes-culturales.index')
            ->with('exito', 'Bien cultural eliminado correctamente.');
    }

    /**
     * Reglas de validación compartidas entre store() y update().
     * `label_ia` debe ser único porque es la llave que usa el modelo de IA.
     */
    private function validarDatos(Request $request, ?int $idActual = null): array
    {
        return $request->validate([
            'label_ia' => ['required', 'string', 'max:191', 'unique:bienes_culturales,label_ia,' . $idActual],
            'nombre' => ['required', 'string', 'max:191'],
            'autor' => ['nullable', 'string', 'max:191'],
            'siglo' => ['nullable', 'string', 'max:50'],
            'resena_historica' => ['required', 'string'],
            'iconografia' => ['nullable', 'string'],
            'imagen' => ['nullable', 'image', 'max:4096'],
            'activo' => ['nullable', 'boolean'],
        ]);
    }
}
