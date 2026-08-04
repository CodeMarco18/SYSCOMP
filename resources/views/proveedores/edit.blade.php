@extends('layouts.app')

@section('title', 'Editar Proveedor - SysComp')
@section('page_title', 'Editar Proveedor')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> Editar Proveedor #{{ $proveedor->ruc }}</h5>

            <form action="{{ route('proveedores.update', $proveedor) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="ruc" class="form-label fw-semibold">RUC (11 dígitos) (*)</label>
                        <input type="text" maxlength="11" name="ruc" id="ruc" class="form-control @error('ruc') is-invalid @enderror" value="{{ old('ruc', $proveedor->ruc) }}" required>
                        @error('ruc') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="razon_social" class="form-label fw-semibold">Razón Social (*)</label>
                        <input type="text" name="razon_social" id="razon_social" class="form-control @error('razon_social') is-invalid @enderror" value="{{ old('razon_social', $proveedor->razon_social) }}" required>
                        @error('razon_social') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $proveedor->telefono) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo', $proveedor->correo) }}">
                        @error('correo') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label for="direccion" class="form-label fw-semibold">Dirección</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion', $proveedor->direccion) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="estado" class="form-label fw-semibold">Estado (*)</label>
                        <select name="estado" id="estado" class="form-select" required>
                            <option value="ACTIVO" {{ old('estado', $proveedor->estado) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="INACTIVO" {{ old('estado', $proveedor->estado) == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning text-dark"><i class="fa-solid fa-rotate me-1"></i> Actualizar Proveedor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
