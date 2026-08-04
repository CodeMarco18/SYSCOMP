@extends('layouts.app')

@section('title', 'Productos - SysComp')
@section('page_title', 'Catálogo de Productos y Control de Stock')

@section('content')
<div class="card card-custom p-4">
    <!-- Filtros y Búsqueda -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <form action="{{ route('productos.index') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por código, nombre, marca..." value="{{ $buscar }}" style="width: 250px;">

            <select name="id_categoria" class="form-select" style="width: 200px;">
                <option value="">-- Todas las Categorías --</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->id_categoria }}" {{ $categoriaId == $cat->id_categoria ? 'selected' : '' }}>
                        {{ $cat->nombre_categoria }}
                    </option>
                @endforeach
            </select>

            <select name="estado" class="form-select" style="width: 150px;">
                <option value="">-- Estado --</option>
                <option value="ACTIVO" {{ $estado == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                <option value="INACTIVO" {{ $estado == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
            </select>

            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-filter"></i> Filtrar</button>
            @if($buscar || $categoriaId || $estado)
                <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </form>

        <a href="{{ route('productos.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Registrar Producto
        </a>
    </div>

    <!-- Tabla Responsiva -->
    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                    <th>Stock Actual</th>
                    <th>Stock Mín.</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $prod)
                <tr>
                    <td><code>{{ $prod->codigo_producto }}</code></td>
                    <td>
                        <strong class="text-dark d-block">{{ $prod->nombre_producto }}</strong>
                        <small class="text-muted">{{ $prod->marca }} {{ $prod->modelo }}</small>
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ $prod->categoria->nombre_categoria }}</span></td>
                    <td>S/ {{ number_format($prod->precio_compra, 2) }}</td>
                    <td class="fw-bold text-success">S/ {{ number_format($prod->precio_venta, 2) }}</td>
                    <td>
                        @if($prod->stock_actual <= $prod->stock_minimo)
                            <span class="badge bg-danger rounded-pill"><i class="fa-solid fa-triangle-exclamation me-1"></i> {{ $prod->stock_actual }} (REPONER)</span>
                        @else
                            <span class="badge bg-success rounded-pill">{{ $prod->stock_actual }}</span>
                        @endif
                    </td>
                    <td>{{ $prod->stock_minimo }}</td>
                    <td>
                        <form action="{{ route('productos.toggle-estado', $prod) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm border-0 bg-transparent" title="Haz clic para cambiar estado">
                                @if($prod->estado == 'ACTIVO')
                                    <span class="badge bg-success">ACTIVO</span>
                                @else
                                    <span class="badge bg-secondary">INACTIVO</span>
                                @endif
                            </button>
                        </form>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('productos.show', $prod) }}" class="btn btn-sm btn-outline-info" title="Ver Detalle">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('productos.edit', $prod) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('productos.destroy', $prod) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Confirma la eliminación del producto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">No hay productos registrados en el sistema.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $productos->links() }}
    </div>
</div>
@endsection
