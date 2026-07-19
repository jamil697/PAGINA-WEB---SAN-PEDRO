<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Misa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CRUD de la agenda litúrgica: horarios de misa, confesiones y catequesis.
 */
class MisaController extends Controller
{
    public function index(): View
    {
        $misas = Misa::orderBy('dia_semana')->orderBy('hora')->paginate(15);

        return view('admin.misas.index', compact('misas'));
    }

    public function create(): View
    {
        return view('admin.misas.form', ['misa' => new Misa()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Misa::create($this->validarDatos($request));

        return redirect()
            ->route('admin.misas.index')
            ->with('exito', 'Horario registrado correctamente.');
    }

    public function edit(Misa $misa): View
    {
        return view('admin.misas.form', compact('misa'));
    }

    public function update(Request $request, Misa $misa): RedirectResponse
    {
        $misa->update($this->validarDatos($request));

        return redirect()
            ->route('admin.misas.index')
            ->with('exito', 'Horario actualizado correctamente.');
    }

    public function destroy(Misa $misa): RedirectResponse
    {
        $misa->delete();

        return redirect()
            ->route('admin.misas.index')
            ->with('exito', 'Horario eliminado correctamente.');
    }

    private function validarDatos(Request $request): array
    {
        return $request->validate([
            'tipo_actividad' => ['required', 'string', 'max:50'],
            'dia_semana' => ['required', 'integer', 'between:0,6'],
            'hora' => ['required', 'date_format:H:i'],
            'observaciones' => ['nullable', 'string', 'max:191'],
            'activo' => ['nullable', 'boolean'],
        ]);
    }
}
