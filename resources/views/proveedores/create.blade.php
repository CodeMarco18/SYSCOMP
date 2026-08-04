@extends('layouts.app')

@section('title', 'Nuevo Proveedor - SysComp')
@section('page_title', 'Registrar Nuevo Proveedor')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-truck-field text-primary me-2"></i> Datos del Proveedor</h5>

            <form action="{{ route('proveedores.store') }}" method="POST">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="ruc" class="form-label fw-semibold">RUC (11 dígitos) (*)</label>
                        <input type="text" maxlength="11" name="ruc" id="ruc" class="form-control @error('ruc') is-invalid @enderror" value="{{ old('ruc') }}" required placeholder="Ej. 20123456789">
                        @error('ruc') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="razon_social" class="form-label fw-semibold">Razón Social (*)</label>
                        <input type="text" name="razon_social" id="razon_social" class="form-control @error('razon_social') is-invalid @enderror" value="{{ old('razon_social') }}" required placeholder="Ej. Deltron Peru S.A.C.">
                        @error('razon_social') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="Ej. 01-7108000">
                    </div>
                    <div class="col-md-6">
                        <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}" placeholder="ventas@deltron.com.pe">
                        @error('correo') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label for="direccion" class="form-label fw-semibold">Dirección</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion') }}" placeholder="Ej. Av. Manuel Olguín 211, Surco">
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
                    <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Guardar Proveedor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
