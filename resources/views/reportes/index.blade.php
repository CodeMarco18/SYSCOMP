@extends('layouts.app')

@section('title', 'Reportes SQL - SysComp')
@section('page_title', 'Reportes Estadísticos y Vistas SQL')

@section('content')
<div class="card card-custom p-4 mb-4">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-filter text-primary me-2"></i> Generador de Reportes y Vistas del Sistema</h5>

    <form action="{{ route('reportes.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="tipo_reporte" class="form-label fw-semibold">Tipo de Reporte / Vista SQL</label>
            <select name="tipo_reporte" id="tipo_reporte" class="form-select" onchange="this.form.submit()">
                <option value="stock_actual" {{ $tipoReporte == 'stock_actual' ? 'selected' : '' }}>Vista: Stock Actual (vw_stock_actual)</option>
                <option value="stock_bajo" {{ $tipoReporte == 'stock_bajo' ? 'selected' : '' }}>Productos con Stock Bajo (Alerta Reponer)</option>
                <option value="baja_rotacion" {{ $tipoReporte == 'baja_rotacion' ? 'selected' : '' }}>Vista: Baja Rotación - 30 Días (vw_baja_rotacion)</option>
                <option value="ventas_diarias" {{ $tipoReporte == 'ventas_diarias' ? 'selected' : '' }}>Vista: Ventas Diarias (vw_ventas_diarias)</option>
                <option value="productos_mas_vendidos" {{ $tipoReporte == 'productos_mas_vendidos' ? 'selected' : '' }}>Top Productos Más Vendidos</option>
                <option value="compras_proveedor" {{ $tipoReporte == 'compras_proveedor' ? 'selected' : '' }}>Acumulado Compras por Proveedor</option>
                <option value="movimientos" {{ $tipoReporte == 'movimientos' ? 'selected' : '' }}>Historial de Movimientos de Inventario</option>
            </select>
        </div>

        <div class="col-md-2">
            <label for="fecha_inicio" class="form-label fw-semibold">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ $fechaInicio }}">
        </div>

        <div class="col-md-2">
            <label for="fecha_fin" class="form-label fw-semibold">Fecha Fin</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ $fechaFin }}">
        </div>

        <div class="col-md-3">
            <label for="id_categoria" class="form-label fw-semibold">Filtrar por Categoría</label>
            <select name="id_categoria" id="id_categoria" class="form-select">
                <option value="">-- Todas las Categorías --</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->id_categoria }}" {{ $categoriaId == $cat->id_categoria ? 'selected' : '' }}>
                        {{ $cat->nombre_categoria }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-chart-pie me-1"></i> Generar</button>
        </div>
    </form>
</div>

<!-- Tabla de Resultados -->
<div class="card card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-dark m-0">
            <i class="fa-solid fa-table me-2 text-primary"></i> Resultado de la Consulta ({{ count($resultados) }} registros encontrados)
        </h6>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print();">
            <i class="fa-solid fa-print me-1"></i> Imprimir Reporte
        </button>
    </div>

    <div class="table-responsive">
        @if($tipoReporte == 'stock_actual' || $tipoReporte == 'stock_bajo')
            <table class="table table-hover table-custom align-middle">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <th>Stock Mín.</th>
                        <th>Estado Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resultados as $row)
                    <tr>
                        <td><code>{{ $row->codigo_producto }}</code></td>
                        <td class="fw-bold text-dark">{{ $row->nombre_producto }}</td>
                        <td>{{ $row->nombre_categoria }}</td>
                        <td class="fw-bold fs-6">{{ $row->stock_actual }}</td>
                        <td>{{ $row->stock_minimo }}</td>
                        <td>
                            @if($row->estado_stock == 'REPONER')
                                <span class="badge bg-danger rounded-pill"><i class="fa-solid fa-triangle-exclamation me-1"></i> REPONER</span>
                            @else
                                <span class="badge bg-success rounded-pill">OK</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No hay datos que coincidan con la consulta.</td></tr>
                    @endforelse
                </tbody>
            </table>

        @elseif($tipoReporte == 'baja_rotacion')
            <table class="table table-hover table-custom align-middle">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto de Baja Rotación (Sin Ventas en >30 días)</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <th>Precio Venta</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resultados as $row)
                    <tr>
                        <td><code>{{ $row->codigo_producto }}</code></td>
                        <td class="fw-bold text-dark">{{ $row->nombre_producto }}</td>
                        <td>{{ $row->nombre_categoria }}</td>
                        <td><span class="badge bg-warning text-dark rounded-pill">{{ $row->stock_actual }} unids.</span></td>
                        <td>S/ {{ number_format($row->precio_venta, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No se registran productos sin rotación en los últimos 30 días.</td></tr>
                    @endforelse
                </tbody>
            </table>

        @elseif($tipoReporte == 'ventas_diarias')
            <table class="table table-hover table-custom align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Número de Ventas</th>
                        <th>Monto Total Recaudado (S/)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resultados as $row)
                    <tr>
                        <td><code>{{ \Carbon\Carbon::parse($row->fecha)->format('d/m/Y') }}</code></td>
                        <td><span class="badge bg-primary rounded-pill">{{ $row->numero_ventas }} ventas</span></td>
                        <td class="fw-bold text-success fs-6">S/ {{ number_format($row->monto_total, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">No hay ventas registradas en el rango de fechas.</td></tr>
                    @endforelse
                </tbody>
            </table>

        @elseif($tipoReporte == 'productos_mas_vendidos')
            <table class="table table-hover table-custom align-middle">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Total Unidades Vendidas</th>
                        <th>Monto Total Generado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resultados as $row)
                    <tr>
                        <td><code>{{ $row->codigo_producto }}</code></td>
                        <td class="fw-bold text-dark">{{ $row->nombre_producto }}</td>
                        <td><span class="badge bg-success rounded-pill fs-6">{{ $row->total_unidades }} unids.</span></td>
                        <td class="fw-bold text-success">S/ {{ number_format($row->total_recaudado, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No hay ventas registradas en el periodo.</td></tr>
                    @endforelse
                </tbody>
            </table>

        @elseif($tipoReporte == 'compras_proveedor')
            <table class="table table-hover table-custom align-middle">
                <thead>
                    <tr>
                        <th>RUC</th>
                        <th>Proveedor</th>
                        <th>Total Compras Realizadas</th>
                        <th>Monto Acumulado (S/)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resultados as $row)
                    <tr>
                        <td><code>{{ $row->ruc }}</code></td>
                        <td class="fw-bold text-dark">{{ $row->razon_social }}</td>
                        <td><span class="badge bg-info text-dark rounded-pill">{{ $row->total_compras }} compras</span></td>
                        <td class="fw-bold text-success">S/ {{ number_format($row->monto_total, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No hay compras registradas para proveedores en el rango.</td></tr>
                    @endforelse
                </tbody>
            </table>

        @elseif($tipoReporte == 'movimientos')
            <table class="table table-hover table-custom align-middle">
                <thead>
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Referencia</th>
                        <th>Stock Resultante</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resultados as $row)
                    <tr>
                        <td><code>{{ \Carbon\Carbon::parse($row->fecha)->format('d/m/Y H:i') }}</code></td>
                        <td><code>{{ $row->codigo_producto }}</code></td>
                        <td class="fw-bold text-dark">{{ $row->nombre_producto }}</td>
                        <td>
                            @if($row->tipo_movimiento == 'ENTRADA')
                                <span class="badge bg-success">ENTRADA</span>
                            @else
                                <span class="badge bg-danger">SALIDA</span>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $row->cantidad }}</td>
                        <td><small class="text-muted">{{ $row->referencia }}</small></td>
                        <td><span class="badge bg-dark">{{ $row->stock_resultante }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No hay movimientos en el periodo seleccionado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
