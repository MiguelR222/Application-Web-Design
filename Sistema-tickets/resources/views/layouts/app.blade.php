<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Tickets - @yield('title', 'Inicio')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .badge-pendiente  { background-color: #6c757d; }
        .badge-en_curso   { background-color: #0d6efd; }
        .badge-en_espera  { background-color: #ffc107; color:#000; }
        .badge-cancelada  { background-color: #dc3545; }
        .badge-finalizada { background-color: #198754; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">🎫 Sistema de Tickets</a>
    <div class="navbar-nav ms-auto d-flex align-items-center gap-2">
        @auth
            @if(auth()->user()->rol === 'admin')
                <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="nav-link text-white" href="{{ url('/tickets') }}">Tickets</a>
                <a class="nav-link text-white" href="{{ route('admin.usuarios.index') }}">Usuarios</a>
            @endif

            @if(auth()->user()->rol === 'gerente')
                <a class="nav-link text-white" href="{{ route('gerente.dashboard') }}">Dashboard</a>
                <a class="nav-link text-white" href="{{ route('gerente.reportes') }}">Reportes</a>
                <a class="nav-link text-white" href="{{ url('/tickets') }}">Tickets</a>
            @endif

            @if(auth()->user()->rol === 'usuario')
                <a class="nav-link text-white" href="{{ route('usuario.dashboard') }}">Mi Panel</a>
                <a class="nav-link text-white" href="{{ route('usuario.tickets.index') }}">Mis Tickets</a>
                <a class="nav-link text-white" href="{{ route('usuario.tickets.create') }}">Nuevo Ticket</a>
            @endif

            <span class="text-white">
                {{ auth()->user()->name }}
                <span class="badge bg-secondary ms-1">{{ auth()->user()->rol }}</span>
            </span>

            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-outline-light">Salir</button>
            </form>
        @endauth

        @guest
            <a class="nav-link text-white" href="{{ route('tickets.create') }}">Crear ticket</a>
            <a class="nav-link text-white" href="{{ route('login') }}">Iniciar sesion</a>
        @endguest
    </div>
</nav>

<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>