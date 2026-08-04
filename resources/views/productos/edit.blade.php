@extends('layouts.app')

@section('title', 'Editar Producto - SysComp')
@section('page_title', 'Editar Producto')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> Editar Producto #{{ $producto->codigo_producto }}</h5>

            <form action="{{ route('productos.update', $producto) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="codigo_producto" class="form-label fw-semibold">Código del Producto (*)</label>
                        <input type="text" name="codigo_producto" id="codigo_producto" class="form-control @error('codigo_producto') is-invalid @enderror" value="{{ old('codigo_producto', $producto->codigo_producto) }}" required>
                        @error('codigo_producto') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-8">
                        <label for="nombre_producto" class="form-label fw-semibold">Nombre del Producto (*)</label>
                        <input type="text" name="nombre_producto" id="nombre_producto" class="form-control @error('nombre_producto') is-invalid @enderror" value="{{ old('nombre_producto', $producto->nombre_producto) }}" required>
                        @error('nombre_producto') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="id_categoria" class="form-label fw-semibold">Categoría (*)</label>
                        <select name="id_categoria" id="id_categoria" class="form-select @error('id_categoria') is-invalid @enderror" required>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id_categoria }}" {{ old('id_categoria', $producto->id_categoria) == $cat->id_categoria ? 'selected' : '' }}>
                                    {{ $cat->nombre_categoria }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_categoria') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="marca" class="form-label fw-semibold">Marca</label>
                        <input type="text" name="marca" id="marca" class="form-control" value="{{ old('marca', $producto->marca) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="modelo" class="form-label fw-semibold">Modelo</label>
                        <input type="text" name="modelo" id="modelo" class="form-control" value="{{ old('modelo', $producto->modelo) }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="numero_serie" class="form-label fw-semibold">Número de Serie (S/N)</label>
                        <input type="text" name="numero_serie" id="numero_serie" class="form-control" value="{{ old('numero_serie', $producto->numero_serie) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="precio_compra" class="form-label fw-semibold">Precio Compra (S/) (*)</label>
                        <input type="number" step="0.01" min="0" name="precio_compra" id="precio_compra" class="form-control @error('precio_compra') is-invalid @enderror" value="{{ old('precio_compra', $producto->precio_compra) }}" required>
                        @error('precio_compra') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="precio_venta" class="form-label fw-semibold">Precio Venta (S/) (*)</label>
                        <input type="number" step="0.01" min="0" name="precio_venta" id="precio_venta" class="form-control @error('precio_venta') is-invalid @enderror" value="{{ old('precio_venta', $producto->precio_venta) }}" required>
                        @error('precio_venta') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="stock_actual" class="form-label fw-semibold">Stock Actual (*)</label>
                        <input type="number" min="0" name="stock_actual" id="stock_actual" class="form-control @error('stock_actual') is-invalid @enderror" value="{{ old('stock_actual', $producto->stock_actual) }}" required>
                        @error('stock_actual') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="stock_minimo" class="form-label fw-semibold">Stock Mínimo Alerta (*)</label>
                        <input type="number" min="0" name="stock_minimo" id="stock_minimo" class="form-control @error('stock_minimo') is-invalid @enderror" value="{{ old('stock_minimo', $producto->stock_minimo) }}" required>
                        @error('stock_minimo') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="estado" class="form-label fw-semibold">Estado (*)</label>
                        <select name="estado" id="estado" class="form-select" required>
                            <option value="ACTIVO" {{ old('estado', $producto->estado) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="INACTIVO" {{ old('estado', $producto->estado) == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning text-dark"><i class="fa-solid fa-rotate me-1"></i> Actualizar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
