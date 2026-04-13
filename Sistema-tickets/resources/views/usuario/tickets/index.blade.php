@extends('layouts.app')

@section('title', 'Mis Tickets')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
	<h2 class="fw-bold mb-0">Mis Tickets</h2>
	<a href="{{ route('usuario.tickets.create') }}" class="btn btn-primary">Nuevo Ticket</a>
</div>

@if($tickets->isEmpty())
	<div class="alert alert-info text-center">
		No tienes tickets registrados. <a href="{{ route('usuario.tickets.create') }}">Crear ticket</a>
	</div>
@else
	<div class="table-responsive">
		<table class="table table-hover bg-white shadow-sm rounded align-middle">
			<thead class="table-dark">
				<tr>
					<th># Reporte</th>
					<th>Departamento</th>
					<th>Categoria</th>
					<th>Urgencia</th>
					<th>Estado</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach($tickets as $ticket)
					<tr>
						<td><code>{{ $ticket->numero_reporte }}</code></td>
						<td>{{ $ticket->departamento }}</td>
						<td>{{ ucfirst($ticket->categoria) }}</td>
						<td>
							@php
								$color = [
									'baja' => 'success',
									'media' => 'info',
									'alta' => 'warning',
									'critica' => 'danger',
								][$ticket->nivel_urgencia] ?? 'secondary';
							@endphp
							<span class="badge bg-{{ $color }}">{{ ucfirst($ticket->nivel_urgencia) }}</span>
						</td>
						<td>
							<span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
						</td>
						<td>
							<a href="{{ route('usuario.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">Ver</a>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endif
@endsection