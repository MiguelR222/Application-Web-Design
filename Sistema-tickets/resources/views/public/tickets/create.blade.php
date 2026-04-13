@extends('layouts.app')

@section('title', 'Crear Ticket')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Crear Ticket de Soporte</h5>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Revisa los datos del formulario.</strong>
                    </div>
                @endif

                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nombre *</label>
                            <input
                                type="text"
                                name="cliente_nombre"
                                class="form-control @error('cliente_nombre') is-invalid @enderror"
                                value="{{ old('cliente_nombre') }}"
                                required
                            >
                            @error('cliente_nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input
                                type="email"
                                name="cliente_email"
                                class="form-control @error('cliente_email') is-invalid @enderror"
                                value="{{ old('cliente_email') }}"
                            >
                            @error('cliente_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Departamento *</label>
                            <input
                                type="text"
                                name="departamento"
                                class="form-control @error('departamento') is-invalid @enderror"
                                value="{{ old('departamento') }}"
                                required
                            >
                            @error('departamento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Categoria *</label>
                            <select name="categoria" class="form-select @error('categoria') is-invalid @enderror" required>
                                <option value="">-- Selecciona --</option>
                                @foreach (['software', 'hardware', 'comunicaciones', 'plataformas', 'email', 'otro'] as $cat)
                                    <option value="{{ $cat }}" @selected(old('categoria') === $cat)>{{ ucfirst($cat) }}</option>
                                @endforeach
                            </select>
                            @error('categoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Urgencia *</label>
                            <select name="nivel_urgencia" class="form-select @error('nivel_urgencia') is-invalid @enderror" required>
                                @foreach (['baja', 'media', 'alta', 'critica'] as $nivel)
                                    <option value="{{ $nivel }}" @selected(old('nivel_urgencia', 'media') === $nivel)>{{ ucfirst($nivel) }}</option>
                                @endforeach
                            </select>
                            @error('nivel_urgencia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Descripcion corta *</label>
                            <input
                                type="text"
                                name="descripcion_corta"
                                class="form-control @error('descripcion_corta') is-invalid @enderror"
                                maxlength="255"
                                value="{{ old('descripcion_corta') }}"
                                required
                            >
                            @error('descripcion_corta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Descripcion detallada</label>
                            <textarea
                                name="descripcion_detallada"
                                class="form-control @error('descripcion_detallada') is-invalid @enderror"
                                rows="4"
                            >{{ old('descripcion_detallada') }}</textarea>
                            @error('descripcion_detallada')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-success">Enviar Ticket</button>
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-secondary">Iniciar sesion</a>
                        @endauth
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
