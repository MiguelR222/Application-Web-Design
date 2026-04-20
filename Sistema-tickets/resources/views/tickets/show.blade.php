@extends('layouts.app')

@section('title', 'Ticket ' . $ticket->numero_reporte)

@section('content')
	<div class="row justify-content-center">
		<div class="col-lg-8">
			<div class="card shadow-sm">
				<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
					<h5 class="mb-0">{{ $ticket->numero_reporte }}</h5>
					<span class="badge bg-light text-dark">
						{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
					</span>
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
							<p class="mb-1 text-muted small">Cliente</p>
							<strong>{{ $ticket->cliente_nombre }}</strong>
						</div>

						<div class="col-md-6">
							<p class="mb-1 text-muted small">Email</p>
							<strong>{{ $ticket->cliente_email ?? '-' }}</strong>
						</div>

						<div class="col-md-6">
							<p class="mb-1 text-muted small">Departamento</p>
							<strong>{{ $ticket->departamento }}</strong>
						</div>

						<div class="col-md-6">
							<p class="mb-1 text-muted small">Categoría</p>
							<strong>{{ ucfirst($ticket->categoria) }}</strong>
						</div>

						<div class="col-md-6">
							<p class="mb-1 text-muted small">Urgencia</p>
							<strong>{{ ucfirst($ticket->nivel_urgencia) }}</strong>
						</div>

						<div class="col-md-6">
							<p class="mb-1 text-muted small">Técnico Asignado</p>
							<strong>{{ $ticket->tecnico_asignado ?? '-' }}</strong>
						</div>

						<div class="col-12">
							<p class="mb-1 text-muted small">Descripción Corta</p>
							<strong>{{ $ticket->descripcion_corta }}</strong>
						</div>

						@if ($ticket->descripcion_detallada)
							<div class="col-12">
								<p class="mb-1 text-muted small">Descripción Detallada</p>
								<p>{{ $ticket->descripcion_detallada }}</p>
							</div>
						@endif

						@if ($ticket->comentarios_tecnico)
							<div class="col-12">
								<p class="mb-1 text-muted small">Comentarios del Técnico</p>
								<p>{{ $ticket->comentarios_tecnico }}</p>
							</div>
						@endif

						<div class="col-md-4">
							<p class="mb-1 text-muted small">Fecha Reporte</p>
							<strong>{{ $ticket->fecha_reporte?->format('d/m/Y H:i') }}</strong>
						</div>

						<div class="col-md-4">
							<p class="mb-1 text-muted small">Fecha Promesa</p>
							<strong>{{ $ticket->fecha_promesa?->format('d/m/Y H:i') ?? '-' }}</strong>
						</div>

						<div class="col-md-4">
							<p class="mb-1 text-muted small">Fecha Resolución</p>
							<strong>{{ $ticket->fecha_resolucion?->format('d/m/Y H:i') ?? '-' }}</strong>
						</div>
					</div>

					<hr class="my-4">
					<h5>Adjuntos del ticket</h5>
					<div class="row">
						@foreach($ticket->attachments as $attachment)
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
										<strong>Diagnóstico IA:</strong>
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
											<strong>Categoría sugerida:</strong>
											<span class="text-muted">{{ $attachment->ai_suggested_category }}</span>
										</div>
									@endif
								@elseif($attachment->ai_status === 'error')
									<div class="small text-danger mt-2">
										No se pudo analizar con IA: {{ $attachment->ai_error }}
									</div>
								@endif
							</div>
						@endforeach
					</div>
				</div>

				<div class="card-footer d-flex gap-2">
					<a href="{{ route('admin.tickets.edit', $ticket) }}" class="btn btn-warning">Editar</a>
					<a href="{{ route('tickets.index') }}" class="btn btn-secondary">Volver</a>
					<form
						action="{{ route('admin.tickets.destroy', $ticket) }}"
						method="POST"
						class="ms-auto"
						onsubmit="return confirm('¿Eliminar?')"
					>
						@csrf
						@method('DELETE')
						<button class="btn btn-danger">Eliminar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection