@extends('layouts.admin')

@section('titulo', $bien->exists ? 'Editar Bien Cultural' : 'Nuevo Bien Cultural')

@section('contenido')
<h3>{{ $bien->exists ? 'Editar' : 'Registrar' }} Bien Cultural</h3>

<form method="POST"
      action="{{ $bien->exists ? route('admin.bienes-culturales.update', $bien) : route('admin.bienes-culturales.store') }}"
      enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
    @csrf
    @if ($bien->exists) @method('PUT') @endif

    <div class="mb-3">
        <label class="form-label">Label IA <small class="text-muted">(debe coincidir con la clase del modelo TensorFlow.js)</small></label>
        <input type="text" name="label_ia" class="form-control @error('label_ia') is-invalid @enderror" value="{{ old('label_ia', $bien->label_ia) }}" required>
        @error('label_ia') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $bien->nombre) }}" required>
        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Autor</label>
            <input type="text" name="autor" class="form-control" value="{{ old('autor', $bien->autor) }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Siglo</label>
            <input type="text" name="siglo" class="form-control" value="{{ old('siglo', $bien->siglo) }}">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Reseña histórica</label>
        <textarea name="resena_historica" rows="4" class="form-control @error('resena_historica') is-invalid @enderror" required>{{ old('resena_historica', $bien->resena_historica) }}</textarea>
        @error('resena_historica') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Iconografía / significado teológico</label>
        <textarea name="iconografia" rows="3" class="form-control">{{ old('iconografia', $bien->iconografia) }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Imagen</label>
        <input type="file" name="imagen" class="form-control" accept="image/*">
        @if ($bien->imagen_path)
            <img src="{{ $bien->imagen_url }}" class="mt-2" style="max-height:120px;">
        @endif
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" name="activo" value="1" class="form-check-input" id="activo" {{ old('activo', $bien->activo ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="activo">Visible al público</label>
    </div>

    <button type="submit" class="btn btn-dark">Guardar</button>
    <a href="{{ route('admin.bienes-culturales.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</form>
@endsection
