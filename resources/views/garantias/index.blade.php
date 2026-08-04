@extends('layouts.app')

@section('title', 'Garantías - SysComp')
@section('page_title', 'Control de Garantías de Productos de Cómputo')

@section('content')
<div class="card card-custom p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <form action="{{ route('garantias.index') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por código garantía, N° serie o producto..." value="{{ $buscar }}" style="width: 320px;">

            <select name="estado" class="form-select" style="width: 170px;">
                <option value="">-- Estado Garantía --</option>
                <option value="VIGENTE" {{ $estado == 'VIGENTE' ? 'selected' : '' }}>VIGENTE</option>
                <option value="VENCIDA" {{ $estado == 'VENCIDA' ? 'selected' : '' }}>VENCIDA</option>
            </select>

            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
            @if($buscar || $estado)
                <a href="{{ route('garantias.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle">
            <thead>
                <tr>
                    <th>Código Garantía</th>
                    <th>Producto / N° Serie</th>
                    <th>Cliente</th>
                    <th>Fecha Inicio</th>
                    <th>Vencimiento</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($garantias as $gar)
                <tr>
                    <td><code>{{ $gar->codigo_garantia }}</code></td>
                    <td>
                        <strong class="text-dark d-block">{{ $gar->detalleVenta->producto->nombre_producto }}</strong>
                        <small class="text-muted">S/N: {{ $gar->detalleVenta->producto->numero_serie ?? 'No registrado' }}</small>
                    </td>
                    <td>
                        <span class="d-block text-dark fw-semibold">{{ $gar->detalleVenta->venta->cliente->nombre_completo }}</span>
                        <small class="text-muted">Doc: {{ $gar->detalleVenta->venta->cliente->documento }}</small>
                    </td>
                    <td>{{ $gar->fecha_inicio->format('d/m/Y') }}</td>
                    <td>{{ $gar->fecha_vencimiento->format('d/m/Y') }}</td>
                    <td>
                        @if($gar->estado == 'VIGENTE')
                            <span class="badge bg-success-subtle text-success border border-success"><i class="fa-solid fa-shield-check me-1"></i> VIGENTE</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger"><i class="fa-solid fa-clock-rotate-left me-1"></i> VENCIDA</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('garantias.show', $gar) }}" class="btn btn-sm btn-outline-info">
                            <i class="fa-solid fa-eye me-1"></i> Ver Certificado
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No hay registros de garantía.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $garantias->links() }}
    </div>
</div>
@endsection
