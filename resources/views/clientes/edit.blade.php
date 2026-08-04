@extends('layouts.app')

@section('title', 'Editar Cliente - SysComp')
@section('page_title', 'Editar Cliente')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-user-pen text-warning me-2"></i> Editar Cliente #{{ $cliente->documento }}</h5>

            <form action="{{ route('clientes.update', $cliente) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="nombres" class="form-label fw-semibold">Nombres (*)</label>
                        <input type="text" name="nombres" id="nombres" class="form-control @error('nombres') is-invalid @enderror" value="{{ old('nombres', $cliente->nombres) }}" required>
                        @error('nombres') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="apellidos" class="form-label fw-semibold">Apellidos (*)</label>
                        <input type="text" name="apellidos" id="apellidos" class="form-control @error('apellidos') is-invalid @enderror" value="{{ old('apellidos', $cliente->apellidos) }}" required>
                        @error('apellidos') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="documento" class="form-label fw-semibold">Documento (*)</label>
                        <input type="text" name="documento" id="documento" class="form-control @error('documento') is-invalid @enderror" value="{{ old('documento', $cliente->documento) }}" required>
                        @error('documento') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo', $cliente->correo) }}">
                        @error('correo') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label for="direccion" class="form-label fw-semibold">Dirección</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion', $cliente->direccion) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="estado" class="form-label fw-semibold">Estado (*)</label>
                        <select name="estado" id="estado" class="form-select" required>
                            <option value="ACTIVO" {{ old('estado', $cliente->estado) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="INACTIVO" {{ old('estado', $cliente->estado) == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning text-dark"><i class="fa-solid fa-rotate me-1"></i> Actualizar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
