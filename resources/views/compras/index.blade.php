@extends('layouts.app')

@section('title', 'Compras - SysComp')
@section('page_title', 'Historial de Compras a Proveedores')

@section('content')
<div class="card card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form action="{{ route('compras.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por proveedor o RUC..." value="{{ $buscar }}">
            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
            @if($buscar)
                <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </form>

        <a href="{{ route('compras.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Registrar Nueva Compra
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle">
            <thead>
                <tr>
                    <th>N° Compra</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Registrado Por</th>
                    <th>Monto Total</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($compras as $compra)
                <tr>
                    <td><code>#COMP-{{ str_pad($compra->id_compra, 6, '0', STR_PAD_LEFT) }}</code></td>
                    <td>{{ $compra->fecha->format('d/m/Y H:i') }}</td>
                    <td>
                        <strong class="text-dark d-block">{{ $compra->proveedor->razon_social }}</strong>
                        <small class="text-muted">RUC: {{ $compra->proveedor->ruc }}</small>
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ $compra->empleado->nombre_completo }}</span></td>
                    <td class="fw-bold text-success">S/ {{ number_format($compra->total, 2) }}</td>
                    <td><span class="badge bg-success">COMPLETADA</span></td>
                    <td class="text-end">
                        <a href="{{ route('compras.show', $compra) }}" class="btn btn-sm btn-outline-info">
                            <i class="fa-solid fa-eye me-1"></i> Detalle
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No se registran compras en el sistema.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $compras->links() }}
    </div>
</div>
@endsection
