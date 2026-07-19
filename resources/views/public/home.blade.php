@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
<div class="container py-4">
    <div class="p-5 mb-4 bg-white rounded-3 shadow-sm text-center">
        <h1>Iglesia San Pedro de Huánuco</h1>
        <p class="lead">Fe, historia y comunidad en el corazón de Huánuco.</p>
        <a href="{{ route('escaneo') }}" class="btn btn-dark btn-lg">
            <i class="fa-solid fa-camera"></i> Descubre nuestro patrimonio cultural
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h3>Horarios</h3>
            @forelse ($misas as $dia => $lista)
                <h6 class="mt-3">{{ $dia }}</h6>
                <ul>
                    @foreach ($lista as $item)
                        <li>{{ $item->tipo_actividad }} — {{ $item->hora }}</li>
                    @endforeach
                </ul>
            @empty
                <p class="text-muted">Aún no se han registrado horarios.</p>
            @endforelse
        </div>

        <div class="col-md-6">
            <h3>Avisos Parroquiales</h3>
            @forelse ($avisos as $aviso)
                <div class="card mb-2">
                    <div class="card-body">
                        <h6 class="card-title">{{ $aviso->titulo }}</h6>
                        <p class="card-text small">{{ Str::limit($aviso->contenido, 120) }}</p>
                    </div>
                </div>
            @empty
                <p class="text-muted">No hay avisos publicados por el momento.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
