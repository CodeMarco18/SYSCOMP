@extends('layouts.app')

@section('title', 'Proveedores - SysComp')
@section('page_title', 'Gestión de Proveedores')

@section('content')
<div class="card card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form action="{{ route('proveedores.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por RUC o Razón Social..." value="{{ $buscar }}">
            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
            @if($buscar)
                <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </form>

        <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Registrar Proveedor
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle">
            <thead>
                <tr>
                    <th>RUC</th>
                    <th>Razón Social</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($proveedores as $prov)
                <tr>
                    <td><code>{{ $prov->ruc }}</code></td>
                    <td class="fw-bold text-dark">{{ $prov->razon_social }}</td>
                    <td>{{ $prov->telefono ?? 'N/A' }}</td>
                    <td>{{ $prov->correo ?? 'N/A' }}</td>
                    <td class="small text-muted">{{ $prov->direccion ?? 'N/A' }}</td>
                    <td>
                        @if($prov->estado == 'ACTIVO')
                            <span class="badge bg-success">ACTIVO</span>
                        @else
                            <span class="badge bg-secondary">INACTIVO</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('proveedores.edit', $prov) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fa-solid fa-pen-to-square"></i> Editar
                        </a>
                        <form action="{{ route('proveedores.destroy', $prov) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar o desactivar este proveedor?');">
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
                    <td colspan="7" class="text-center py-4 text-muted">No hay proveedores registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $proveedores->links() }}
    </div>
</div>
@endsection
