@extends('layouts.app')

@section('title', 'Movimientos de Inventario - SysComp')
@section('page_title', 'Historial y Kardex de Movimientos de Inventario')

@section('content')
<div class="card card-custom p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <form action="{{ route('movimientos.index') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por referencia o producto..." value="{{ $buscar }}" style="width: 250px;">

            <select name="id_producto" class="form-select" style="width: 250px;">
                <option value="">-- Filtrar por Producto --</option>
                @foreach($productos as $prod)
                    <option value="{{ $prod->id_producto }}" {{ $productoId == $prod->id_producto ? 'selected' : '' }}>
                        {{ $prod->nombre_producto }}
                    </option>
                @endforeach
            </select>

            <select name="tipo_movimiento" class="form-select" style="width: 170px;">
                <option value="">-- Tipo Movimiento --</option>
                <option value="ENTRADA" {{ $tipoMovimiento == 'ENTRADA' ? 'selected' : '' }}>ENTRADA</option>
                <option value="SALIDA" {{ $tipoMovimiento == 'SALIDA' ? 'selected' : '' }}>SALIDA</option>
            </select>

            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-filter"></i> Filtrar</button>
            @if($buscar || $productoId || $tipoMovimiento)
                <a href="{{ route('movimientos.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle">
            <thead>
                <tr>
                    <th>Fecha y Hora</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Referencia</th>
                    <th>Stock Resultante</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimientos as $mov)
                <tr>
                    <td><code>{{ $mov->fecha->format('d/m/Y H:i:s') }}</code></td>
                    <td>
                        <strong class="text-dark d-block">{{ $mov->producto->nombre_producto }}</strong>
                        <small class="text-muted">Cod: {{ $mov->producto->codigo_producto }}</small>
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ $mov->producto->categoria->nombre_categoria }}</span></td>
                    <td>
                        @if($mov->tipo_movimiento == 'ENTRADA')
                            <span class="badge bg-success-subtle text-success border border-success fw-bold">
                                <i class="fa-solid fa-arrow-down me-1"></i> ENTRADA
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger fw-bold">
                                <i class="fa-solid fa-arrow-up me-1"></i> SALIDA
                            </span>
                        @endif
                    </td>
                    <td class="fw-bold fs-6">{{ $mov->cantidad }}</td>
                    <td><small class="text-muted">{{ $mov->referencia }}</small></td>
                    <td>
                        <span class="badge bg-dark rounded-pill fs-6">{{ $mov->stock_resultante }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No hay movimientos de inventario registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $movimientos->links() }}
    </div>
</div>
@endsection
