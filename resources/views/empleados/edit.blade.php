@extends('layouts.app')

@section('title', 'Editar Empleado - SysComp')
@section('page_title', 'Editar Empleado')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-user-gear text-warning me-2"></i> Editar Empleado #{{ $empleado->documento }}</h5>

            <form action="{{ route('empleados.update', $empleado) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="nombres" class="form-label fw-semibold">Nombres (*)</label>
                        <input type="text" name="nombres" id="nombres" class="form-control @error('nombres') is-invalid @enderror" value="{{ old('nombres', $empleado->nombres) }}" required>
                        @error('nombres') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="apellidos" class="form-label fw-semibold">Apellidos (*)</label>
                        <input type="text" name="apellidos" id="apellidos" class="form-control @error('apellidos') is-invalid @enderror" value="{{ old('apellidos', $empleado->apellidos) }}" required>
                        @error('apellidos') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="documento" class="form-label fw-semibold">Documento (*)</label>
                        <input type="text" name="documento" id="documento" class="form-control @error('documento') is-invalid @enderror" value="{{ old('documento', $empleado->documento) }}" required>
                        @error('documento') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="cargo" class="form-label fw-semibold">Cargo (*)</label>
                        <select name="cargo" id="cargo" class="form-select @error('cargo') is-invalid @enderror" required>
                            <option value="Administrador" {{ old('cargo', $empleado->cargo) == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                            <option value="Vendedor" {{ old('cargo', $empleado->cargo) == 'Vendedor' ? 'selected' : '' }}>Vendedor</option>
                            <option value="Almacenero" {{ old('cargo', $empleado->cargo) == 'Almacenero' ? 'selected' : '' }}>Almacenero</option>
                            <option value="Técnico de Soporte" {{ old('cargo', $empleado->cargo) == 'Técnico de Soporte' ? 'selected' : '' }}>Técnico de Soporte</option>
                        </select>
                        @error('cargo') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $empleado->telefono) }}">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo', $empleado->correo) }}">
                        @error('correo') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="estado" class="form-label fw-semibold">Estado (*)</label>
                        <select name="estado" id="estado" class="form-select" required>
                            <option value="ACTIVO" {{ old('estado', $empleado->estado) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="INACTIVO" {{ old('estado', $empleado->estado) == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning text-dark"><i class="fa-solid fa-rotate me-1"></i> Actualizar Empleado</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
