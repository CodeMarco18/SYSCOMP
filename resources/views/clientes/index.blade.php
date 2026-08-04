@extends('layouts.app')

@section('title', 'Clientes - SysComp')
@section('page_title', 'Gestión de Clientes')

@section('content')
<div class="card card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form action="{{ route('clientes.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por documento o nombre..." value="{{ $buscar }}">
            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
            @if($buscar)
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </form>

        <a href="{{ route('clientes.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Registrar Cliente
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle">
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Nombres y Apellidos</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $cli)
                <tr>
                    <td><code>{{ $cli->documento }}</code></td>
                    <td class="fw-bold text-dark">{{ $cli->nombre_completo }}</td>
                    <td>{{ $cli->telefono ?? 'N/A' }}</td>
                    <td>{{ $cli->correo ?? 'N/A' }}</td>
                    <td class="small text-muted">{{ $cli->direccion ?? 'N/A' }}</td>
                    <td>
                        @if($cli->estado == 'ACTIVO')
                            <span class="badge bg-success">ACTIVO</span>
                        @else
                            <span class="badge bg-secondary">INACTIVO</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('clientes.edit', $cli) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fa-solid fa-pen-to-square"></i> Editar
                        </a>
                        <form action="{{ route('clientes.destroy', $cli) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Confirma la eliminación del cliente?');">
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
                    <td colspan="7" class="text-center py-4 text-muted">No se encontraron clientes registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $clientes->links() }}
    </div>
</div>
@endsection
