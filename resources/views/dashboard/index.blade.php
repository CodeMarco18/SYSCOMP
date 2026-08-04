@extends('layouts.app')

@section('title', 'Dashboard - SysComp')
@section('page_title', 'Panel de Control Principal')

@section('content')
<!-- Tarjetas de Métricas -->
<div class="row g-3 mb-4">
    <!-- Ventas Hoy -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card bg-primary shadow-sm">
            <i class="fa-solid fa-money-bill-wave bg-icon"></i>
            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.75rem;">Ventas de Hoy</span>
            <h3 class="fw-bold my-1">S/ {{ number_format($ventasHoy, 2) }}</h3>
            <span class="small text-white-50"><i class="fa-solid fa-calendar-day me-1"></i> Día actual</span>
        </div>
    </div>

    <!-- Ventas del Mes -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card bg-success shadow-sm">
            <i class="fa-solid fa-chart-line bg-icon"></i>
            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.75rem;">Ventas del Mes</span>
            <h3 class="fw-bold my-1">S/ {{ number_format($ventasMes, 2) }}</h3>
            <span class="small text-white-50"><i class="fa-solid fa-calendar-week me-1"></i> Mes en curso</span>
        </div>
    </div>

    <!-- Stock Bajo -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card bg-danger shadow-sm">
            <i class="fa-solid fa-triangle-exclamation bg-icon"></i>
            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.75rem;">Stock Bajo</span>
            <h3 class="fw-bold my-1">{{ $stockBajoCount }}</h3>
            <span class="small text-white-50"><i class="fa-solid fa-boxes-packing me-1"></i> Requieren reposición</span>
        </div>
    </div>

    <!-- Baja Rotación -->
    <div class="col-xl-3 col-md-6">
        <div class="metric-card bg-warning text-dark shadow-sm">
            <i class="fa-solid fa-hourglass-half bg-icon text-dark"></i>
            <span class="text-dark-50 text-uppercase fw-semibold" style="font-size: 0.75rem;">Baja Rotación</span>
            <h3 class="fw-bold my-1 text-dark">{{ $bajaRotacionCount }}</h3>
            <span class="small text-dark-50"><i class="fa-solid fa-clock me-1"></i> Sin ventas >30 días</span>
        </div>
    </div>
</div>

<!-- Segunda Fila de Métricas Totales -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-custom p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-circle bg-primary-subtle p-3 text-primary fs-4">
                <i class="fa-solid fa-box-archive"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Total Productos</span>
                <h4 class="fw-bold m-0">{{ $totalProductos }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-circle bg-info-subtle p-3 text-info fs-4">
                <i class="fa-solid fa-tags"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Categorías</span>
                <h4 class="fw-bold m-0">{{ $totalCategorias }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-circle bg-success-subtle p-3 text-success fs-4">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Clientes</span>
                <h4 class="fw-bold m-0">{{ $totalClientes }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-circle bg-secondary-subtle p-3 text-secondary fs-4">
                <i class="fa-solid fa-truck-field"></i>
            </div>
            <div>
                <span class="text-muted small d-block">Proveedores</span>
                <h4 class="fw-bold m-0">{{ $totalProveedores }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Gráficos Estadísticos -->
<div class="row g-4 mb-4">
    <!-- Gráfico 1: Ventas por Día -->
    <div class="col-lg-6">
        <div class="card card-custom p-3 h-100">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-chart-area text-primary me-2"></i> Ventas por Día (Últimos 7 días)</h6>
            <div style="height: 260px;">
                <canvas id="chartVentasDia"></canvas>
            </div>
        </div>
    </div>

    <!-- Gráfico 2: Ventas Mensuales -->
    <div class="col-lg-6">
        <div class="card card-custom p-3 h-100">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-chart-bar text-success me-2"></i> Evolución de Ventas Mensuales (Año {{ date('Y') }})</h6>
            <div style="height: 260px;">
                <canvas id="chartVentasMes"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Gráfico 3: Productos más Vendidos -->
    <div class="col-lg-6">
        <div class="card card-custom p-3 h-100">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-award text-warning me-2"></i> Top 5 Productos Más Vendidos</h6>
            <div style="height: 260px;">
                <canvas id="chartProductosTop"></canvas>
            </div>
        </div>
    </div>

    <!-- Gráfico 4: Stock por Categoría -->
    <div class="col-lg-6">
        <div class="card card-custom p-3 h-100">
            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-chart-pie text-info me-2"></i> Distribución de Stock por Categoría</h6>
            <div style="height: 260px;">
                <canvas id="chartStockCat"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Datos PHP a JS
        const ventasDiaData = @json($ventasPorDia);
        const ventasMesData = @json($mesesData);
        const productosMasVendidos = @json($productosMasVendidos);
        const stockPorCategoria = @json($stockPorCategoria);

        // 1. Chart Ventas por Día
        new Chart(document.getElementById('chartVentasDia'), {
            type: 'line',
            data: {
                labels: ventasDiaData.map(item => item.fecha),
                datasets: [{
                    label: 'Monto Total (S/)',
                    data: ventasDiaData.map(item => item.monto_total),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.15)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 3
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // 2. Chart Ventas Mensuales
        new Chart(document.getElementById('chartVentasMes'), {
            type: 'bar',
            data: {
                labels: ventasMesData.map(item => item.mes),
                datasets: [{
                    label: 'Ventas (S/)',
                    data: ventasMesData.map(item => item.total),
                    backgroundColor: '#10b981',
                    borderRadius: 6
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // 3. Chart Productos Top
        new Chart(document.getElementById('chartProductosTop'), {
            type: 'bar',
            data: {
                labels: productosMasVendidos.map(item => item.nombre_producto),
                datasets: [{
                    label: 'Unidades Vendidas',
                    data: productosMasVendidos.map(item => item.total_vendido),
                    backgroundColor: '#f59e0b',
                    borderRadius: 6
                }]
            },
            options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false }
        });

        // 4. Chart Stock Por Categoria
        new Chart(document.getElementById('chartStockCat'), {
            type: 'doughnut',
            data: {
                labels: stockPorCategoria.map(item => item.nombre_categoria),
                datasets: [{
                    data: stockPorCategoria.map(item => item.stock_total || 0),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    });
</script>
@endpush
