@extends('layouts.app')

@section('title', 'Empleados - SysComp')
@section('page_title', 'Gestión de Empleados del Sistema')

@section('content')
<div class="card card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form action="{{ route('empleados.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por documento o nombre..." value="{{ $buscar }}">
            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
            @if($buscar)
                <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </form>

        <a href="{{ route('empleados.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Registrar Empleado
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle">
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Nombres y Apellidos</th>
                    <th>Cargo</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empleados as $emp)
                <tr>
                    <td><code>{{ $emp->documento }}</code></td>
                    <td class="fw-bold text-dark">{{ $emp->nombre_completo }}</td>
                    <td><span class="badge bg-info-subtle text-info border border-info">{{ $emp->cargo }}</span></td>
                    <td>{{ $emp->telefono ?? 'N/A' }}</td>
                    <td>{{ $emp->correo ?? 'N/A' }}</td>
                    <td>
                        @if($emp->estado == 'ACTIVO')
                            <span class="badge bg-success">ACTIVO</span>
                        @else
                            <span class="badge bg-secondary">INACTIVO</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('empleados.edit', $emp) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fa-solid fa-pen-to-square"></i> Editar
                        </a>
                        <form action="{{ route('empleados.destroy', $emp) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar o desactivar este empleado?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fa-solid fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No hay empleados registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $empleados->links() }}
    </div>
</div>
@endsection
