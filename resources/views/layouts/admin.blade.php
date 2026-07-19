<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Panel Administrativo')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">Panel Administrativo — Iglesia San Pedro</span>
            <div class="d-flex">
                <a href="{{ route('admin.bienes-culturales.index') }}" class="btn btn-sm btn-outline-light me-2">Bienes Culturales</a>
                <a href="{{ route('admin.misas.index') }}" class="btn btn-sm btn-outline-light me-2">Horarios</a>
                <a href="{{ route('admin.avisos.index') }}" class="btn btn-sm btn-outline-light me-2">Avisos</a>
                <a href="{{ route('admin.intenciones.index') }}" class="btn btn-sm btn-outline-light">Intenciones</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @if (session('exito'))
            <div class="alert alert-success">{{ session('exito') }}</div>
        @endif

        @yield('contenido')
    </div>
</body>
</html>
