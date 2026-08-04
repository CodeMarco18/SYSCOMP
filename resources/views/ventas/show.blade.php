@extends('layouts.app')

@section('title', 'Comprobante de Venta - SysComp')
@section('page_title', 'Comprobante de Venta ' . $venta->tipo_comprobante . ' ' . $venta->serie . '-' . $venta->numero)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card card-custom p-4" id="seccionImprimir">
            <div class="d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom">
                <div>
                    <h4 class="fw-bold text-dark m-0"><i class="fa-solid fa-laptop-code text-primary me-2"></i> SYSCOMP TECH STORE</h4>
                    <p class="text-muted small m-0">Venta de Equipos de Cómputo, Componentes y Laptops</p>
                    <p class="text-muted small m-0">RUC: 20601234567 | Av. Tecnología 450, Lima</p>
                </div>
                <div class="text-end border p-3 rounded-3 bg-light">
                    <h5 class="fw-bold text-primary m-0">{{ $venta->tipo_comprobante }} ELECTRÓNICA</h5>
                    <h6 class="fw-bold text-dark m-0 mt-1">N° {{ $venta->serie }}-{{ $venta->numero }}</h6>
                    <span class="badge bg-success mt-2">PAGADO / VENTA COMPLETADA</span>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <h6 class="fw-bold text-secondary mb-2"><i class="fa-solid fa-user me-1"></i> Cliente:</h6>
                    <p class="m-0"><strong>Nombre / Razón Social:</strong> {{ $venta->cliente->nombre_completo }}</p>
                    <p class="m-0"><strong>DNI / RUC:</strong> {{ $venta->cliente->documento }}</p>
                    <p class="m-0"><strong>Dirección:</strong> {{ $venta->cliente->direccion ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="fw-bold text-secondary mb-2"><i class="fa-solid fa-calendar me-1"></i> Datos de Emisión:</h6>
                    <p class="m-0"><strong>Fecha de Emisión:</strong> {{ $venta->fecha->format('d/m/Y H:i') }}</p>
                    <p class="m-0"><strong>Atendido por:</strong> {{ $venta->empleado->nombre_completo }}</p>
                    <p class="m-0"><strong>Moneda:</strong> SOLES (S/)</p>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered table-custom align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Descripción del Producto</th>
                            <th class="text-center">Cant.</th>
                            <th class="text-end">Precio Unit. (S/)</th>
                            <th class="text-end">Subtotal (S/)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalles as $det)
                        <tr>
                            <td><code>{{ $det->producto->codigo_producto }}</code></td>
                            <td>
                                <strong class="text-dark d-block">{{ $det->producto->nombre_producto }}</strong>
                                @if($det->garantia)
                                    <small class="text-primary d-block mt-1">
                                        <i class="fa-solid fa-shield-halved me-1"></i> Garantía: <code>{{ $det->garantia->codigo_garantia }}</code> (Vence: {{ $det->garantia->fecha_vencimiento->format('d/m/Y') }})
                                    </small>
                                @endif
                            </td>
                            <td class="text-center fw-bold">{{ $det->cantidad }}</td>
                            <td class="text-end">S/ {{ number_format($det->precio_unitario, 2) }}</td>
                            <td class="text-end fw-bold text-success">S/ {{ number_format($det->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end fs-5">TOTAL COMPROBANTE:</th>
                            <th class="text-end fs-4 text-success fw-bold">S/ {{ number_format($venta->total, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="p-3 bg-light rounded-3 text-muted small mb-4">
                <p class="mb-1"><strong>Términos de Garantía:</strong> Todos los productos cuentan con garantía de tienda de 12 meses por defectos de fabricación. Presentar este comprobante para cualquier atención técnica.</p>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('ventas.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Volver a Ventas</a>
                <button type="button" class="btn btn-outline-primary" onclick="window.print();"><i class="fa-solid fa-print me-1"></i> Imprimir Comprobante</button>
            </div>
        </div>
    </div>
</div>
@endsection
