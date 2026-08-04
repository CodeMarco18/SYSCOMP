@extends('layouts.app')

@section('title', 'Nuevo Producto - SysComp')
@section('page_title', 'Registrar Nuevo Producto de Cómputo')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-box text-primary me-2"></i> Formulario de Registro de Producto</h5>

            <form action="{{ route('productos.store') }}" method="POST">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="codigo_producto" class="form-label fw-semibold">Código del Producto (*)</label>
                        <input type="text" name="codigo_producto" id="codigo_producto" class="form-control @error('codigo_producto') is-invalid @enderror" value="{{ old('codigo_producto') }}" required placeholder="Ej. LAP-RTX4060-01">
                        @error('codigo_producto') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-8">
                        <label for="nombre_producto" class="form-label fw-semibold">Nombre del Producto (*)</label>
                        <input type="text" name="nombre_producto" id="nombre_producto" class="form-control @error('nombre_producto') is-invalid @enderror" value="{{ old('nombre_producto') }}" required placeholder="Ej. Laptop ASUS TUF Gaming F15 Core i7 16GB SSD 512GB">
                        @error('nombre_producto') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="id_categoria" class="form-label fw-semibold">Categoría (*)</label>
                        <select name="id_categoria" id="id_categoria" class="form-select @error('id_categoria') is-invalid @enderror" required>
                            <option value="">-- Seleccionar Categoría --</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id_categoria }}" {{ old('id_categoria') == $cat->id_categoria ? 'selected' : '' }}>
                                    {{ $cat->nombre_categoria }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_categoria') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="marca" class="form-label fw-semibold">Marca</label>
                        <input type="text" name="marca" id="marca" class="form-control" value="{{ old('marca') }}" placeholder="Ej. ASUS, Kingston, Intel">
                    </div>
                    <div class="col-md-4">
                        <label for="modelo" class="form-label fw-semibold">Modelo</label>
                        <input type="text" name="modelo" id="modelo" class="form-control" value="{{ old('modelo') }}" placeholder="Ej. TUF-FX507">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="numero_serie" class="form-label fw-semibold">Número de Serie (S/N)</label>
                        <input type="text" name="numero_serie" id="numero_serie" class="form-control" value="{{ old('numero_serie') }}" placeholder="Ej. SN-994857102">
                    </div>
                    <div class="col-md-4">
                        <label for="precio_compra" class="form-label fw-semibold">Precio Compra (S/) (*)</label>
                        <input type="number" step="0.01" min="0" name="precio_compra" id="precio_compra" class="form-control @error('precio_compra') is-invalid @enderror" value="{{ old('precio_compra') }}" required placeholder="0.00">
                        @error('precio_compra') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="precio_venta" class="form-label fw-semibold">Precio Venta (S/) (*)</label>
                        <input type="number" step="0.01" min="0" name="precio_venta" id="precio_venta" class="form-control @error('precio_venta') is-invalid @enderror" value="{{ old('precio_venta') }}" required placeholder="0.00">
                        @error('precio_venta') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="stock_actual" class="form-label fw-semibold">Stock Inicial (*)</label>
                        <input type="number" min="0" name="stock_actual" id="stock_actual" class="form-control @error('stock_actual') is-invalid @enderror" value="{{ old('stock_actual', 0) }}" required>
                        @error('stock_actual') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="stock_minimo" class="form-label fw-semibold">Stock Mínimo Alerta (*)</label>
                        <input type="number" min="0" name="stock_minimo" id="stock_minimo" class="form-control @error('stock_minimo') is-invalid @enderror" value="{{ old('stock_minimo', 5) }}" required>
                        @error('stock_minimo') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="estado" class="form-label fw-semibold">Estado (*)</label>
                        <select name="estado" id="estado" class="form-select" required>
                            <option value="ACTIVO" {{ old('estado') == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="INACTIVO" {{ old('estado') == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
