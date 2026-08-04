@extends('layouts.app')

@section('title', 'Ventas - SysComp')
@section('page_title', 'Módulo de Ventas de Cómputo')

@section('content')
<div class="card card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form action="{{ route('ventas.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por comprobante o cliente..." value="{{ $buscar }}">
            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
            @if($buscar)
                <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </form>

        <a href="{{ route('ventas.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-cart-shopping me-1"></i> Realizar Nueva Venta
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle">
            <thead>
                <tr>
                    <th>Comprobante</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Atendido Por</th>
                    <th>Monto Total</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventas as $v)
                <tr>
                    <td>
                        <span class="badge bg-dark">{{ $v->tipo_comprobante }}</span>
                        <code>{{ $v->serie }}-{{ $v->numero }}</code>
                    </td>
                    <td>{{ $v->fecha->format('d/m/Y H:i') }}</td>
                    <td>
                        <strong class="text-dark d-block">{{ $v->cliente->nombre_completo }}</strong>
                        <small class="text-muted">Doc: {{ $v->cliente->documento }}</small>
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ $v->empleado->nombre_completo }}</span></td>
                    <td class="fw-bold text-success fs-6">S/ {{ number_format($v->total, 2) }}</td>
                    <td><span class="badge bg-success">COMPLETADA</span></td>
                    <td class="text-end">
                        <a href="{{ route('ventas.show', $v) }}" class="btn btn-sm btn-outline-info">
                            <i class="fa-solid fa-receipt me-1"></i> Boleta/Factura
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No se registran ventas procesadas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $ventas->links() }}
    </div>
</div>
@endsection
