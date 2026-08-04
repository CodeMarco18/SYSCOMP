@extends('layouts.app')

@section('title', 'Detalle de Compra - SysComp')
@section('page_title', 'Comprobante de Compra #COMP-' . str_pad($compra->id_compra, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h4 class="fw-bold text-dark m-0">COMPRA DE MERCADERÍA</h4>
                    <span class="text-muted">ID Transacción: #COMP-{{ str_pad($compra->id_compra, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="text-end">
                    <span class="badge bg-success fs-6">COMPLETADA</span>
                    <span class="d-block text-muted small mt-1">{{ $compra->fecha->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary mb-2"><i class="fa-solid fa-truck-field me-1"></i> Datos del Proveedor</h6>
                    <p class="m-0"><strong>Razón Social:</strong> {{ $compra->proveedor->razon_social }}</p>
                    <p class="m-0"><strong>RUC:</strong> {{ $compra->proveedor->ruc }}</p>
                    <p class="m-0"><strong>Teléfono:</strong> {{ $compra->proveedor->telefono ?? 'N/A' }}</p>
                    <p class="m-0"><strong>Correo:</strong> {{ $compra->proveedor->correo ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary mb-2"><i class="fa-solid fa-user-check me-1"></i> Empleado de Almacén</h6>
                    <p class="m-0"><strong>Registrado por:</strong> {{ $compra->empleado->nombre_completo }}</p>
                    <p class="m-0"><strong>Cargo:</strong> {{ $compra->empleado->cargo }}</p>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-boxes-stacked me-1"></i> Productos Ingresados al Inventario</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-custom align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio Unit. (S/)</th>
                            <th class="text-end">Subtotal (S/)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compra->detalles as $det)
                        <tr>
                            <td><code>{{ $det->producto->codigo_producto }}</code></td>
                            <td class="fw-semibold">{{ $det->producto->nombre_producto }}</td>
                            <td class="text-center fw-bold">{{ $det->cantidad }}</td>
                            <td class="text-end">S/ {{ number_format($det->precio_unitario, 2) }}</td>
                            <td class="text-end fw-bold">S/ {{ number_format($det->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end fs-5">TOTAL COMPRA:</th>
                            <th class="text-end fs-5 text-success fw-bold">S/ {{ number_format($compra->total, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('compras.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Volver a Compras</a>
            </div>
        </div>
    </div>
</div>
@endsection
