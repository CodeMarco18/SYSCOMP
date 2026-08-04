@extends('layouts.app')

@section('title', 'Nueva Categoría - SysComp')
@section('page_title', 'Registrar Nueva Categoría')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-folder-plus text-primary me-2"></i> Datos de la Categoría</h5>

            <form action="{{ route('categorias.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nombre_categoria" class="form-label fw-semibold">Nombre de la Categoría (*)</label>
                    <input type="text" name="nombre_categoria" id="nombre_categoria" class="form-control @error('nombre_categoria') is-invalid @enderror" value="{{ old('nombre_categoria') }}" required placeholder="Ej. Laptops Gamer">
                    @error('nombre_categoria')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror" placeholder="Descripción opcional de la categoría...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Guardar Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
