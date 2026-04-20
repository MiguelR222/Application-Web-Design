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
                @if ($ticket->ai_executive_summary)
                    <div class="alert alert-info">
                        <strong>Resumen ejecutivo IA:</strong>
                        <p class="mb-0 mt-2">{{ $ticket->ai_executive_summary }}</p>
                    </div>
                @endif

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

                <hr class="my-4">
                <h5>Adjuntos del ticket</h5>
                <div class="row">
                    @forelse($ticket->attachments as $attachment)
                        <div class="col-md-3 mb-3">
                            @if(str_starts_with($attachment->mime_type, 'image/'))
                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank">
                                    <img src="{{ Storage::url($attachment->file_path) }}" class="img-fluid rounded shadow-sm" style="max-height: 180px; object-fit: cover;">
                                </a>
                            @else
                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-outline-primary d-block text-truncate">
                                    {{ $attachment->original_name }}
                                </a>
                            @endif

                            <small class="text-muted">{{ $attachment->original_name }}</small>

                            @if($attachment->ai_status === 'ok')
                                <div class="small mt-2">
                                    <strong>Diagnostico IA:</strong>
                                    <div>{{ $attachment->ai_technical_description }}</div>
                                </div>

                                @if($attachment->ai_ocr_text)
                                    <div class="small mt-1">
                                        <strong>OCR:</strong>
                                        <div class="text-muted">{{ $attachment->ai_ocr_text }}</div>
                                    </div>
                                @endif

                                @if(!empty($attachment->ai_possible_causes))
                                    <div class="small mt-1">
                                        <strong>Causas probables:</strong>
                                        <div class="text-muted">{{ is_array($attachment->ai_possible_causes) ? implode(', ', $attachment->ai_possible_causes) : $attachment->ai_possible_causes }}</div>
                                    </div>
                                @endif

                                @if($attachment->ai_suggested_category)
                                    <div class="small mt-1">
                                        <strong>Categoria sugerida:</strong>
                                        <span class="text-muted">{{ $attachment->ai_suggested_category }}</span>
                                    </div>
                                @endif
                            @elseif($attachment->ai_status === 'error')
                                <div class="small text-danger mt-2">
                                    No se pudo analizar con IA: {{ $attachment->ai_error }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-12 text-muted">Este ticket no tiene adjuntos.</div>
                    @endforelse
                </div>

                <div class="mt-4">
                    <a href="{{ route('usuario.tickets.index') }}" class="btn btn-outline-secondary">Volver a mis tickets</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
