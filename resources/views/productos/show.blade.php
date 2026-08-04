@extends('layouts.app')

@section('title', 'Detalle de Producto - SysComp')
@section('page_title', 'Ficha Técnica de Producto')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card card-custom p-4 text-center">
            <div class="bg-light rounded-circle p-4 d-inline-block mx-auto mb-3 text-primary fs-1">
                <i class="fa-solid fa-laptop text-primary"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">{{ $producto->nombre_producto }}</h5>
            <span class="badge bg-primary rounded-pill mb-3">{{ $producto->categoria->nombre_categoria }}</span>

            <ul class="list-group list-group-flush text-start small">
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Código:</span>
                    <code>{{ $producto->codigo_producto }}</code>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Marca / Modelo:</span>
                    <span class="fw-semibold">{{ $producto->marca }} {{ $producto->modelo }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">N° Serie:</span>
                    <span>{{ $producto->numero_serie ?? 'N/A' }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Precio Compra:</span>
                    <span>S/ {{ number_format($producto->precio_compra, 2) }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Precio Venta:</span>
                    <strong class="text-success fs-6">S/ {{ number_format($producto->precio_venta, 2) }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Stock Actual:</span>
                    <span class="badge {{ $producto->stock_actual <= $producto->stock_minimo ? 'bg-danger' : 'bg-success' }} fs-6">
                        {{ $producto->stock_actual }} unids.
                    </span>
                </li>
            </ul>

            <div class="mt-4">
                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning text-dark w-100">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Editar Producto
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i> Historial Reciente de Movimientos</h5>

            <div class="table-responsive">
                <table class="table table-hover table-custom align-middle">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo Movimiento</th>
                            <th>Cantidad</th>
                            <th>Referencia</th>
                            <th>Stock Resultante</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($producto->movimientos->take(10) as $mov)
                        <tr>
                            <td class="small">{{ $mov->fecha->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($mov->tipo_movimiento == 'ENTRADA')
                                    <span class="badge bg-success-subtle text-success border border-success"><i class="fa-solid fa-arrow-down me-1"></i> ENTRADA</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger"><i class="fa-solid fa-arrow-up me-1"></i> SALIDA</span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $mov->cantidad }}</td>
                            <td><small class="text-muted">{{ $mov->referencia }}</small></td>
                            <td><span class="badge bg-light text-dark border">{{ $mov->stock_resultante }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">No hay movimientos registrados para este producto.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
