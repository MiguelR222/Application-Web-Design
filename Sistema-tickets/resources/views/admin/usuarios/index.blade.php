@extends('layouts.app')

@section('title', 'Gestionar Usuarios')

@section('content')
<div class="container py-4">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h2 class="mb-0 text-primary">Gestion de Usuarios</h2>
		<a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Volver</a>
	</div>

	<div class="card shadow-sm">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-striped table-hover mb-0 align-middle">
					<thead class="table-dark">
						<tr>
							<th>Nombre</th>
							<th>Email</th>
							<th>Rol actual</th>
							<th style="min-width: 260px;">Cambiar rol</th>
						</tr>
					</thead>
					<tbody>
						@forelse($usuarios as $usuario)
							<tr>
								<td>{{ $usuario->name }}</td>
								<td>{{ $usuario->email }}</td>
								<td>
									<span class="badge bg-secondary">{{ $usuario->rol }}</span>
								</td>
								<td>
									<form action="{{ route('admin.usuarios.cambiar-rol', $usuario) }}" method="POST" class="d-flex gap-2">
										@csrf
										@method('PATCH')
										<select name="rol" class="form-select" required>
											<option value="admin" @selected($usuario->rol === 'admin')>admin</option>
											<option value="gerente" @selected($usuario->rol === 'gerente')>gerente</option>
											<option value="usuario" @selected($usuario->rol === 'usuario')>usuario</option>
										</select>
										<button type="submit" class="btn btn-primary">Guardar</button>
									</form>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="4" class="text-center py-4 text-muted">No hay usuarios registrados.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection
