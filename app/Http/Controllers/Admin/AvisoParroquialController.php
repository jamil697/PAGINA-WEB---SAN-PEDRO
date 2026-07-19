<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvisoParroquial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * CRUD de avisos y eventos parroquiales (noticias, festividades, campañas).
 */
class AvisoParroquialController extends Controller
{
    public function index(): View
    {
        $avisos = AvisoParroquial::latest('fecha_publicacion')->paginate(10);

        return view('admin.avisos.index', compact('avisos'));
    }

    public function create(): View
    {
        return view('admin.avisos.form', ['aviso' => new AvisoParroquial()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validarDatos($request);
        $datos['user_id'] = $request->user()->id;

        if ($request->hasFile('imagen')) {
            $datos['imagen_path'] = $request->file('imagen')->store('avisos', 'public');
        }

        AvisoParroquial::create($datos);

        return redirect()
            ->route('admin.avisos.index')
            ->with('exito', 'Aviso publicado correctamente.');
    }

    public function edit(AvisoParroquial $aviso): View
    {
        return view('admin.avisos.form', compact('aviso'));
    }

    public function update(Request $request, AvisoParroquial $aviso): RedirectResponse
    {
        $datos = $this->validarDatos($request);

        if ($request->hasFile('imagen')) {
            if ($aviso->imagen_path) {
                Storage::disk('public')->delete($aviso->imagen_path);
            }
            $datos['imagen_path'] = $request->file('imagen')->store('avisos', 'public');
        }

        $aviso->update($datos);

        return redirect()
            ->route('admin.avisos.index')
            ->with('exito', 'Aviso actualizado correctamente.');
    }

    public function destroy(AvisoParroquial $aviso): RedirectResponse
    {
        if ($aviso->imagen_path) {
            Storage::disk('public')->delete($aviso->imagen_path);
        }

        $aviso->delete();

        return redirect()
            ->route('admin.avisos.index')
            ->with('exito', 'Aviso eliminado correctamente.');
    }

    private function validarDatos(Request $request): array
    {
        return $request->validate([
            'titulo' => ['required', 'string', 'max:191'],
            'contenido' => ['required', 'string'],
            'fecha_publicacion' => ['required', 'date'],
            'fecha_evento' => ['nullable', 'date'],
            'imagen' => ['nullable', 'image', 'max:4096'],
            'activo' => ['nullable', 'boolean'],
        ]);
    }
}
