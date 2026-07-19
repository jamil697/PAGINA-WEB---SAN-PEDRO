@extends('layouts.admin')

@section('titulo', 'Bienes Culturales')

@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Inventario Histórico-Cultural</h3>
    <a href="{{ route('admin.bienes-culturales.create') }}" class="btn btn-dark">+ Nuevo Bien Cultural</a>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Label IA</th>
            <th>Nombre</th>
            <th>Autor / Siglo</th>
            <th>Activo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bienes as $bien)
            <tr>
                <td><code>{{ $bien->label_ia }}</code></td>
                <td>{{ $bien->nombre }}</td>
                <td>{{ $bien->autor }} @if($bien->siglo) ({{ $bien->siglo }}) @endif</td>
                <td>{{ $bien->activo ? 'Sí' : 'No' }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.bienes-culturales.edit', $bien) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                    <form action="{{ route('admin.bienes-culturales.destroy', $bien) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este bien cultural?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $bienes->links() }}
@endsection
