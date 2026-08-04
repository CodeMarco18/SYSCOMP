@extends('layouts.app')

@section('title', 'Editar Categoría - SysComp')
@section('page_title', 'Editar Categoría')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> Modificar Categoría #{{ $categoria->id_categoria }}</h5>

            <form action="{{ route('categorias.update', $categoria) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nombre_categoria" class="form-label fw-semibold">Nombre de la Categoría (*)</label>
                    <input type="text" name="nombre_categoria" id="nombre_categoria" class="form-control @error('nombre_categoria') is-invalid @enderror" value="{{ old('nombre_categoria', $categoria->nombre_categoria) }}" required>
                    @error('nombre_categoria')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                    @error('descripcion')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning text-dark"><i class="fa-solid fa-rotate me-1"></i> Actualizar Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
