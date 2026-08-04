@extends('layouts.app')

@section('title', 'Certificado de Garantía - SysComp')
@section('page_title', 'Certificado de Garantía Técnica')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4">
            <div class="text-center mb-4 pb-3 border-bottom">
                <i class="fa-solid fa-shield-halved text-primary fs-1 mb-2"></i>
                <h4 class="fw-bold text-dark m-0">CERTIFICADO DE GARANTÍA OFICIAL</h4>
                <p class="text-muted small">Cómputo, Laptops y Componentes de Alta Tecnología</p>
                <span class="badge {{ $garantia->estado == 'VIGENTE' ? 'bg-success' : 'bg-danger' }} fs-6">
                    ESTADO: {{ $garantia->estado }}
                </span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <h6 class="fw-bold text-secondary mb-2">Información del Certificado:</h6>
                    <p class="m-0"><strong>Código Garantía:</strong> <code>{{ $garantia->codigo_garantia }}</code></p>
                    <p class="m-0"><strong>Periodo de Cobertura:</strong> {{ $garantia->periodo_meses }} Meses</p>
                    <p class="m-0"><strong>Fecha de Inicio:</strong> {{ $garantia->fecha_inicio->format('d/m/Y') }}</p>
                    <p class="m-0"><strong>Fecha de Vencimiento:</strong> {{ $garantia->fecha_vencimiento->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-secondary mb-2">Información del Titular:</h6>
                    <p class="m-0"><strong>Cliente:</strong> {{ $garantia->detalleVenta->venta->cliente->nombre_completo }}</p>
                    <p class="m-0"><strong>Documento:</strong> {{ $garantia->detalleVenta->venta->cliente->documento }}</p>
                    <p class="m-0"><strong>Comprobante de Origen:</strong> {{ $garantia->detalleVenta->venta->tipo_comprobante }} {{ $garantia->detalleVenta->venta->serie }}-{{ $garantia->detalleVenta->venta->numero }}</p>
                </div>
            </div>

            <div class="bg-light p-3 rounded-3 mb-4 border">
                <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-laptop me-1 text-primary"></i> Producto Cubierto:</h6>
                <p class="m-0 fs-5 fw-bold text-dark">{{ $garantia->detalleVenta->producto->nombre_producto }}</p>
                <p class="m-0 text-muted"><strong>Código:</strong> {{ $garantia->detalleVenta->producto->codigo_producto }} | <strong>S/N:</strong> {{ $garantia->detalleVenta->producto->numero_serie ?? 'N/A' }}</p>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-2">Observaciones y Condiciones:</h6>
                <p class="text-muted small m-0">{{ $garantia->observaciones ?? 'Sin observaciones adicionales.' }}</p>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('garantias.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Volver a Garantías</a>
                <button type="button" class="btn btn-outline-primary" onclick="window.print();"><i class="fa-solid fa-print me-1"></i> Imprimir Certificado</button>
            </div>
        </div>
    </div>
</div>
@endsection
