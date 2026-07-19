@extends('layouts.app')

@section('titulo', 'Intenciones de Misa')

@section('contenido')
<div class="container py-4">
    <div class="col-md-6 mx-auto">
        <h2 class="mb-4">Enviar Intención de Misa</h2>

        <form method="POST" action="{{ route('intenciones.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nombre completo</label>
                <input type="text" name="nombre_solicitante" class="form-control @error('nombre_solicitante') is-invalid @enderror" value="{{ old('nombre_solicitante') }}" required>
                @error('nombre_solicitante') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Correo (opcional)</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Teléfono (opcional)</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Misa para la que solicita la intención</label>
                <select name="misa_id" class="form-select">
                    <option value="">-- Seleccionar (opcional) --</option>
                    @foreach ($misas as $misa)
                        <option value="{{ $misa->id }}">{{ $misa->tipo_actividad }} — {{ $misa->hora }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Fecha de la misa</label>
                <input type="date" name="fecha_misa" class="form-control @error('fecha_misa') is-invalid @enderror" value="{{ old('fecha_misa') }}" required>
                @error('fecha_misa') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Intención</label>
                <textarea name="intencion" rows="4" class="form-control @error('intencion') is-invalid @enderror" required>{{ old('intencion') }}</textarea>
                @error('intencion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-dark w-100">Enviar intención</button>
        </form>
    </div>
</div>
@endsection
