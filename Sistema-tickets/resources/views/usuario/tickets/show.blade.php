@extends('layouts.app')

@section('title', 'Detalle de Ticket')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ticket {{ $ticket->numero_reporte }}</h5>
                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Departamento:</strong>
                        <div>{{ $ticket->departamento }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Categoria:</strong>
                        <div>{{ ucfirst($ticket->categoria) }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Nivel de urgencia:</strong>
                        <div>{{ ucfirst($ticket->nivel_urgencia) }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Fecha de reporte:</strong>
                        <div>{{ optional($ticket->fecha_reporte)->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="col-12">
                        <strong>Descripcion corta:</strong>
                        <div>{{ $ticket->descripcion_corta }}</div>
                    </div>
                    <div class="col-12">
                        <strong>Descripcion detallada:</strong>
                        <div>{{ $ticket->descripcion_detallada ?: '-' }}</div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('usuario.tickets.index') }}" class="btn btn-outline-secondary">Volver a mis tickets</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
