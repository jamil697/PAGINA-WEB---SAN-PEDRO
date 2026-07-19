@extends('layouts.app')

@section('titulo', 'Escaneo de Bienes Culturales')

@push('estilos')
<style>
    #contenedor-camara {
        position: relative;
        max-width: 480px;
        margin: 0 auto;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
    }

    #video-camara {
        width: 100%;
        display: block;
    }

    /* Canvas usado como recuadro guía sobre el video */
    #recuadro-guia {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    #panel-resultado {
        max-width: 480px;
        margin: 1.5rem auto 0;
        display: none;
    }

    #estado-deteccion {
        max-width: 480px;
        margin: 0.75rem auto 0;
        text-align: center;
    }
</style>
@endpush

@section('contenido')
<div class="container py-4">
    <h2 class="text-center mb-1">Módulo de Reconocimiento de Bienes Culturales</h2>
    <p class="text-center text-muted mb-4">
        Apunta la cámara hacia un santo, lienzo o reliquia del templo para conocer su historia.
    </p>

    <div id="contenedor-camara">
        <video id="video-camara" autoplay playsinline muted></video>
        <canvas id="recuadro-guia"></canvas>
    </div>

    <!-- Canvas oculto usado únicamente para capturar frames y pasarlos al modelo -->
    <canvas id="canvas-captura" style="display:none;"></canvas>

    <div id="estado-deteccion" class="text-muted">
        <span class="spinner-border spinner-border-sm" role="status"></span>
        Cargando modelo de reconocimiento...
    </div>

    <div id="panel-resultado" class="card shadow-sm">
        <div class="card-body">
            <h4 id="resultado-nombre" class="card-title"></h4>
            <h6 id="resultado-autor-siglo" class="card-subtitle mb-2 text-muted"></h6>
            <p id="resultado-resena" class="card-text"></p>
            <div id="resultado-iconografia-wrapper" style="display:none;">
                <hr>
                <strong>Significado iconográfico:</strong>
                <p id="resultado-iconografia" class="card-text"></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- TensorFlow.js + el formato de modelo exportado por Teachable Machine --}}
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.20.0/dist/tf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@0.8.5/dist/teachablemachine-image.min.js"></script>

<script>
    // Variables globales expuestas a escaneo.js
    window.CONFIG_IA = {
        // Ruta donde debe copiarse el modelo exportado desde Teachable Machine
        // (model.json, weights.bin, metadata.json) → public/modelo-ia/
        urlModelo: "{{ asset('modelo-ia/') }}/",

        // Endpoint de Laravel que resuelve la etiqueta detectada contra la BD
        urlApiBusqueda: "{{ route('api.bienes-culturales.buscar') }}",

        // Confianza mínima (0 a 1) para considerar válida una detección
        umbralConfianza: 0.85,
    };
</script>
<script src="{{ asset('js/escaneo.js') }}"></script>
@endpush
