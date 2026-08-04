@extends('layouts.app')

@section('title', 'Nuevo Cliente - SysComp')
@section('page_title', 'Registrar Nuevo Cliente')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-user-plus text-primary me-2"></i> Datos del Cliente</h5>

            <form action="{{ route('clientes.store') }}" method="POST">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="nombres" class="form-label fw-semibold">Nombres (*)</label>
                        <input type="text" name="nombres" id="nombres" class="form-control @error('nombres') is-invalid @enderror" value="{{ old('nombres') }}" required placeholder="Ej. Carlos Alberto">
                        @error('nombres') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="apellidos" class="form-label fw-semibold">Apellidos (*)</label>
                        <input type="text" name="apellidos" id="apellidos" class="form-control @error('apellidos') is-invalid @enderror" value="{{ old('apellidos') }}" required placeholder="Ej. Mendoza Ramos">
                        @error('apellidos') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="documento" class="form-label fw-semibold">Documento DNI / RUC (*)</label>
                        <input type="text" name="documento" id="documento" class="form-control @error('documento') is-invalid @enderror" value="{{ old('documento') }}" required placeholder="Ej. 74839201">
                        @error('documento') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="telefono" class="form-label fw-semibold">Teléfono / Celular</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="Ej. 987654321">
                    </div>
                    <div class="col-md-4">
                        <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}" placeholder="carlos@gmail.com">
                        @error('correo') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label for="direccion" class="form-label fw-semibold">Dirección de Entrega</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion') }}" placeholder="Ej. Av. Arequipa 1230, Lima">
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
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Guardar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
