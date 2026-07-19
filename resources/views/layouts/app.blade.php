<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Iglesia San Pedro de Huánuco')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @stack('estilos')
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Iglesia San Pedro de Huánuco</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuPrincipal">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('intenciones.create') }}">Intenciones de Misa</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('escaneo') }}"><i class="fa-solid fa-camera"></i> Escanear Bienes Culturales</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @if (session('exito'))
            <div class="container mt-3">
                <div class="alert alert-success">{{ session('exito') }}</div>
            </div>
        @endif

        @yield('contenido')
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <small>&copy; {{ date('Y') }} Iglesia San Pedro de Huánuco — Servicio Social Universitario UDH</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
